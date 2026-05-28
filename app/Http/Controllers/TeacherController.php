<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Student;
use App\Models\Assessment;
use App\Models\Attendance;
use App\Models\RemedialTask;
use App\Models\Feedback;
use App\Models\Subject;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\RemedialTaskSubmission;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    /**
     * Display the Teacher Dashboard showing students, average marks, and Slow Learner detection.
     */
    public function dashboard(Request $request)
    {
        $classCodes = User::where('role', 'student')->pluck('class_code')->unique()->filter()->values();
        $selectedClass = $request->get('class_code', 'all');

        $query = User::where('role', 'student')->with('student');
        if ($selectedClass !== 'all') {
            $query->where('class_code', $selectedClass);
        }
        $students = $query->get();

        $slowLearnersCount = 0;
        $normalCount = 0;

        foreach ($students as $student) {
            $studentIds = [$student->id];
            try {
                $studentIds[] = new \MongoDB\BSON\ObjectId($student->id);
            } catch (\Exception $e) {}

            $assessments = Assessment::whereIn('student_id', $studentIds)->get();
            
            $totalObtainedAll = 0;
            $totalMaxAll = 0;
            $hasFailingSubject = false;

            if ($assessments->count() > 0) {
                foreach ($assessments as $ast) {
                    $o = $ast->obtained;
                    $m = $ast->max_possible;
                    
                    $totalObtainedAll += $o;
                    $totalMaxAll += $m;

                    $subjectPercentage = $ast->percentage;
                    if ($subjectPercentage < 40) {
                        $hasFailingSubject = true;
                    }
                }
                
                $average = $totalMaxAll > 0 ? round(($totalObtainedAll / $totalMaxAll) * 100, 1) : 0;
                $student->average_marks = $average;
                
                // Flag student as Slow Learner for teacher if ANY subject is below 40% threshold
                if ($hasFailingSubject) {
                    $student->status = "Slow Learner";
                    $slowLearnersCount++;
                } else {
                    $student->status = "Normal Student";
                    $normalCount++;
                }
            } else {
                $student->average_marks = null;
                $student->status = "No Marks";
            }

            // Attendance calculation
            $totalDays = Attendance::whereIn('student_id', $studentIds)->count();
            $presentDays = Attendance::whereIn('student_id', $studentIds)->where('status', 'present')->count();
            $student->attendance_percentage = ($totalDays > 0) ? round(($presentDays / $totalDays) * 100, 1) : 0;
        }

        return view('teacher.dashboard', compact('students', 'slowLearnersCount', 'normalCount', 'classCodes', 'selectedClass'));
    }

    /* =========================================================================
     * ASSESSMENTS (MARKS CRUD)
     * ========================================================================= */

    /**
     * Show assessments page.
     */
    public function assessments()
    {
        $students = User::where('role', 'student')->get();
        $assessments = Assessment::with(['student', 'subjectRelation'])->orderBy('created_at', 'desc')->get();
        $subjects = Subject::orderBy('code', 'asc')->get();
        return view('teacher.assessments.index', compact('students', 'assessments', 'subjects'));
    }

    /**
     * Store assessment mark (single component at a time: ca1, ca2, or end_term).
     */
    public function storeAssessment(Request $request)
    {
        $request->validate([
            'student_id' => 'required|string',
            'subject_id' => 'required|string',
            'component' => 'required|string|in:ca1,ca2,end_term',
            'score' => 'required|numeric|min:0',
            'remedial_resource' => 'nullable|string|max:1000',
        ]);

        $subject = Subject::findOrFail($request->subject_id);

        $maxLimit = 30;
        if ($request->component === 'end_term') {
            $maxLimit = 40;
        }

        if (floatval($request->score) > $maxLimit) {
            return redirect()->back()->withErrors(['score' => "Score for {$request->component} cannot exceed maximum limit of {$maxLimit} marks."])->withInput();
        }

        // Fetch or create new record for that student & subject using robust String/ObjectId matching
        $studentIds = [$request->student_id];
        try {
            $studentIds[] = new \MongoDB\BSON\ObjectId($request->student_id);
        } catch (\Exception $e) {}

        $subjectIds = [$subject->id];
        try {
            $subjectIds[] = new \MongoDB\BSON\ObjectId($subject->id);
        } catch (\Exception $e) {}

        $assessment = Assessment::whereIn('student_id', $studentIds)
            ->whereIn('subject_id', $subjectIds)
            ->first();

        if (!$assessment) {
            $assessment = new Assessment([
                'student_id' => $request->student_id,
                'subject_id' => $subject->id,
            ]);
        }

        $assessment->subject = $subject->name;
        $assessment->teacher_id = auth()->id();
        $assessment->remedial_resource = $request->remedial_resource;
        
        $component = $request->component;
        $assessment->$component = floatval($request->score);
        $assessment->save();

        return redirect()->route('teacher.assessments')->with('success', 'Assessment marks recorded successfully.');
    }

    /**
     * Show edit form for assessment mark.
     */
    public function editAssessment($id)
    {
        $assessment = Assessment::findOrFail($id);
        $students = User::where('role', 'student')->get();
        $subjects = Subject::orderBy('code', 'asc')->get();
        return view('teacher.assessments.edit', compact('assessment', 'students', 'subjects'));
    }

    /**
     * Update assessment mark (single component at a time).
     */
    public function updateAssessment(Request $request, $id)
    {
        $assessment = Assessment::findOrFail($id);

        $request->validate([
            'student_id' => 'required|string',
            'subject_id' => 'required|string',
            'component' => 'required|string|in:ca1,ca2,end_term',
            'score' => 'required|numeric|min:0',
            'remedial_resource' => 'nullable|string|max:1000',
        ]);

        $subject = Subject::findOrFail($request->subject_id);

        $maxLimit = 30;
        if ($request->component === 'end_term') {
            $maxLimit = 40;
        }

        if (floatval($request->score) > $maxLimit) {
            return redirect()->back()->withErrors(['score' => "Score for {$request->component} cannot exceed maximum limit of {$maxLimit} marks."])->withInput();
        }

        $component = $request->component;
        
        $assessment->update([
            'student_id' => $request->student_id,
            'subject_id' => $subject->id,
            'subject' => $subject->name,
            $component => floatval($request->score),
            'remedial_resource' => $request->remedial_resource,
        ]);

        return redirect()->route('teacher.assessments')->with('success', 'Assessment marks updated successfully.');
    }

    /**
     * Delete assessment mark.
     */
    public function destroyAssessment($id)
    {
        $assessment = Assessment::findOrFail($id);
        $assessment->delete();

        return redirect()->route('teacher.assessments')->with('success', 'Assessment marks deleted successfully.');
    }

    /* =========================================================================
     * ATTENDANCE MODULE
     * ========================================================================= */

    /**
     * Show attendance page with list of students filtered by class.
     */
    public function attendance(Request $request)
    {
        $classCodes = User::where('role', 'student')->pluck('class_code')->unique()->filter()->values();
        $selectedClass = $request->get('class_code', $classCodes->first() ?? 'BCA-3A');

        $students = User::where('role', 'student')
            ->where('class_code', $selectedClass)
            ->with('student')
            ->get();

        $attendanceLogs = Attendance::with('student')->orderBy('created_at', 'desc')->take(50)->get();
        return view('teacher.attendance.index', compact('students', 'attendanceLogs', 'classCodes', 'selectedClass'));
    }

    /**
     * Store attendance for students.
     */
    public function storeAttendance(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'class_code' => 'required|string',
            'status' => 'required|array', // key: student_id, value: present/absent
        ]);

        foreach ($request->status as $studentId => $status) {
            Attendance::updateOrCreate(
                [
                    'student_id' => $studentId,
                    'date' => $request->date,
                ],
                [
                    'status' => $status,
                ]
            );
        }

        return redirect()->route('teacher.attendance', ['class_code' => $request->class_code])
            ->with('success', 'Attendance marked successfully for Section ' . $request->class_code . ' on ' . $request->date);
    }

    /* =========================================================================
     * REMEDIAL TASKS
     * ========================================================================= */

    /**
     * Show remedial tasks.
     */
    public function tasks()
    {
        $students = User::where('role', 'student')->with('student')->get();
        $subjects = Subject::orderBy('code', 'asc')->get();

        // All unique class codes for the class filter dropdown
        $classCodes = User::where('role', 'student')
            ->pluck('class_code')
            ->unique()
            ->filter()
            ->sort()
            ->values();

        // Compile mapping: subject_id => [array of student_ids whose dynamic percentage is below 40%]
        $failingMap = [];
        foreach ($subjects as $subj) {
            $failingMap[$subj->id] = [];
        }

        foreach ($students as $student) {
            $studentIds = [$student->id];
            try {
                $studentIds[] = new \MongoDB\BSON\ObjectId($student->id);
            } catch (\Exception $e) {}

            $assessments = Assessment::whereIn('student_id', $studentIds)->get();
            foreach ($assessments as $ast) {
                if ($ast->percentage < 40) {
                    $failingMap[$ast->subject_id][] = $student->id;
                }
            }
        }

        $tasks = RemedialTask::with(['student', 'subject'])->orderBy('created_at', 'desc')->get();
        return view('teacher.tasks.index', compact('students', 'tasks', 'subjects', 'failingMap', 'classCodes'));
    }

    /**
     * Store remedial task (supports multiple students at once).
     */
    public function storeTask(Request $request)
    {
        $request->validate([
            'student_ids' => 'required|array',
            'student_ids.*' => 'required|string',
            'subject_id' => 'required|string',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        foreach ($request->student_ids as $studentId) {
            RemedialTask::create([
                'student_id' => $studentId,
                'subject_id' => $request->subject_id,
                'teacher_id' => auth()->id(),
                'title' => $request->title,
                'description' => $request->description,
                'status' => 'pending',
            ]);
        }

        return redirect()->route('teacher.tasks')->with('success', 'Remedial task(s) assigned successfully.');
    }

    /* =========================================================================
     * FEEDBACK MODULE
     * ========================================================================= */

    /**
     * Show feedback.
     */
    public function feedback()
    {
        $students = User::where('role', 'student')->get();
        $feedbacks = Feedback::where('teacher_id', auth()->id())->with('student')->orderBy('created_at', 'desc')->get();
        return view('teacher.feedback.index', compact('students', 'feedbacks'));
    }

    /**
     * Store feedback.
     */
    public function storeFeedback(Request $request)
    {
        $request->validate([
            'student_id' => 'required|string',
            'remark' => 'required|string|max:1000',
        ]);

        Feedback::create([
            'student_id' => $request->student_id,
            'teacher_id' => auth()->id(),
            'remark' => $request->remark,
        ]);

        return redirect()->route('teacher.feedback')->with('success', 'Feedback remark submitted successfully.');
    }

    /* =========================================================================
     * ASSIGNMENTS MANAGEMENT & REVIEW PANELS
     * ========================================================================= */

    /**
     * Show all assignments created by the teacher.
     */
    public function assignments()
    {
        $subjects = Subject::orderBy('code', 'asc')->get();
        
        // Fetch all unique class codes for student target mapping
        $classCodes = User::where('role', 'student')->pluck('class_code')->unique()->filter()->sort()->values();

        $assignments = Assignment::where('teacher_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('teacher.assignments.index', compact('assignments', 'subjects', 'classCodes'));
    }

    /**
     * Create/Store a new assignment.
     */
    public function storeAssignment(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'subject' => 'required|string',
            'class_code' => 'required|string',
            'due_date' => 'required|date',
            'max_score' => 'required|integer|min:1',
        ]);

        $assignment = Assignment::create([
            'teacher_id' => auth()->id(),
            'title' => $request->title,
            'description' => $request->description,
            'subject' => $request->subject,
            'class_code' => $request->class_code,
            'due_date' => $request->due_date,
            'max_score' => intval($request->max_score),
        ]);

        // Pre-populate AssignmentSubmission tracking records for all students in this class
        $students = User::where('role', 'student')->where('class_code', $request->class_code)->get();
        foreach ($students as $student) {
            AssignmentSubmission::firstOrCreate(
                [
                    'assignment_id' => $assignment->id,
                    'student_id' => $student->id,
                ],
                [
                    'status' => 'pending',
                    'file_url' => null,
                    'submitted_at' => null,
                ]
            );
        }

        return redirect()->route('teacher.assignments')->with('success', 'Assignment created successfully.');
    }

    /**
     * Assignment Review Panel (List student submissions, search/filter, pagination).
     */
    public function assignmentSubmissions(Request $request)
    {
        $search = $request->get('search');
        $statusFilter = $request->get('status');

        $teacherIds = [auth()->id()];
        try {
            $teacherIds[] = new \MongoDB\BSON\ObjectId(auth()->id());
        } catch (\Exception $e) {}

        // Fetch all assignments created by this teacher
        $assignments = Assignment::whereIn('teacher_id', $teacherIds)->get();

        $trackingList = collect();
        foreach ($assignments as $assignment) {
            // Find all students in this assignment's targeted class
            $studentsInClass = User::where('role', 'student')->where('class_code', $assignment->class_code)->get();
            foreach ($studentsInClass as $student) {
                // Fetch submission if exists (with robust type fallback)
                $studentIds = [$student->id];
                try {
                    $studentIds[] = new \MongoDB\BSON\ObjectId($student->id);
                } catch (\Exception $e) {}

                $assignmentIds = [$assignment->id];
                try {
                    $assignmentIds[] = new \MongoDB\BSON\ObjectId($assignment->id);
                } catch (\Exception $e) {}

                $submission = AssignmentSubmission::whereIn('assignment_id', $assignmentIds)
                    ->whereIn('student_id', $studentIds)
                    ->first();

                $status = $submission ? $submission->status : 'pending_upload';

                $trackingList->push([
                    'assignment' => $assignment,
                    'student' => $student,
                    'submission' => $submission,
                    'status' => $status,
                ]);
            }
        }

        // Apply filters
        if (!empty($statusFilter)) {
            $trackingList = $trackingList->filter(function ($item) use ($statusFilter) {
                return $item['status'] === $statusFilter;
            });
        }

        if (!empty($search)) {
            $searchLower = strtolower($search);
            $trackingList = $trackingList->filter(function ($item) use ($searchLower) {
                $studentName = strtolower($item['student']->name ?? '');
                $assignmentTitle = strtolower($item['assignment']->title ?? '');
                return str_contains($studentName, $searchLower) || str_contains($assignmentTitle, $searchLower);
            });
        }

        // Pagination
        $perPage = 10;
        $page = request()->get('page', 1);
        $total = $trackingList->count();
        
        $paginatedSubmissions = new \Illuminate\Pagination\LengthAwarePaginator(
            $trackingList->forPage($page, $perPage)->values(),
            $total,
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return view('teacher.assignments.submissions', [
            'submissions' => $paginatedSubmissions,
            'search' => $search,
            'statusFilter' => $statusFilter,
        ]);
    }

    /**
     * Review/Grade an assignment submission (Accept, Reject, Score, Feedback, Private Notes).
     */
    public function reviewAssignmentSubmission(Request $request, $id)
    {
        $submission = AssignmentSubmission::findOrFail($id);

        // Security check: teacher must own the assignment
        if ($submission->assignment->teacher_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'action' => 'required|string|in:accept,reject',
            'score' => 'required|numeric|min:0|max:' . ($submission->assignment->max_score ?? 100),
            'feedback' => 'nullable|string|max:5000',
            'teacher_notes' => 'nullable|string|max:5000', // Private teacher-only note
        ]);

        $status = $request->action === 'accept' ? 'accepted' : 'rejected';

        $submission->update([
            'status' => $status,
            'score' => floatval($request->score),
            'feedback' => $request->feedback,
            'teacher_notes' => $request->teacher_notes,
            'reviewed_at' => now(),
        ]);

        return redirect()->route('teacher.assignments.submissions')->with('success', 'Submission reviewed successfully.');
    }

    /**
     * Remedial Review Panel (only shows remedial task submissions assigned by this teacher).
     */
    public function remedialSubmissions(Request $request)
    {
        $search = $request->get('search');
        $statusFilter = $request->get('status');

        $teacherIds = [auth()->id()];
        try {
            $teacherIds[] = new \MongoDB\BSON\ObjectId(auth()->id());
        } catch (\Exception $e) {}

        // Fetch remedial tasks assigned by this teacher
        $taskIds = RemedialTask::whereIn('teacher_id', $teacherIds)->pluck('id')->toArray();

        // Convert taskIds to handle both String and BSON ObjectId forms
        $allTaskIds = [];
        foreach ($taskIds as $id) {
            $allTaskIds[] = $id;
            try {
                $allTaskIds[] = new \MongoDB\BSON\ObjectId($id);
            } catch (\Exception $e) {}
        }

        $query = RemedialTaskSubmission::whereIn('remedial_task_id', $allTaskIds)
            ->with(['remedialTask.subject', 'student']);

        if (!empty($statusFilter)) {
            $query->where('status', $statusFilter);
        }

        $submissions = $query->orderBy('submitted_at', 'desc')->get();

        // Search filter (in-memory for student name or task title)
        if (!empty($search)) {
            $searchLower = strtolower($search);
            $submissions = $submissions->filter(function ($sub) use ($searchLower) {
                $studentName = strtolower($sub->student->name ?? '');
                $taskTitle = strtolower($sub->remedialTask->title ?? '');
                return str_contains($studentName, $searchLower) || str_contains($taskTitle, $searchLower);
            });
        }

        // Pagination
        $perPage = 10;
        $page = request()->get('page', 1);
        $total = $submissions->count();
        
        $paginatedSubmissions = new \Illuminate\Pagination\LengthAwarePaginator(
            $submissions->forPage($page, $perPage),
            $total,
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return view('teacher.remedials.submissions', [
            'submissions' => $paginatedSubmissions,
            'search' => $search,
            'statusFilter' => $statusFilter,
        ]);
    }

    /**
     * Review/Grade a remedial task submission privately.
     */
    public function reviewRemedialSubmission(Request $request, $id)
    {
        $submission = RemedialTaskSubmission::findOrFail($id);

        // Security check: teacher must own the task
        if ($submission->remedialTask->teacher_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'action' => 'required|string|in:accept,reject',
            'score' => 'required|numeric|min:0|max:' . ($submission->remedialTask->max_score ?? 100),
            'feedback' => 'nullable|string|max:5000',
            'teacher_notes' => 'nullable|string|max:5000',
        ]);

        $status = $request->action === 'accept' ? 'accepted' : 'rejected';

        $submission->update([
            'status' => $status,
            'score' => floatval($request->score),
            'feedback' => $request->feedback,
            'teacher_notes' => $request->teacher_notes,
            'reviewed_at' => now(),
        ]);

        // Also sync the main task status: if accepted, mark the main RemedialTask as completed
        if ($status === 'accepted') {
            $submission->remedialTask->update([
                'status' => 'completed',
                'completed_at' => now()
            ]);
        } else {
            $submission->remedialTask->update([
                'status' => 'pending' // Revert to pending since it was rejected
            ]);
        }

        return redirect()->route('teacher.remedials.submissions')->with('success', 'Remedial submission reviewed successfully.');
    }
}
