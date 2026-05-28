<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Student;
use App\Models\Assessment;
use App\Models\Attendance;
use App\Models\RemedialTask;
use App\Models\Feedback;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\RemedialTaskSubmission;
use App\Services\CloudinaryService;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * Helper to get student details and metrics.
     */
    private function getStudentMetrics($studentId)
    {
        $studentUser = User::where('id', $studentId)->with('student')->firstOrFail();
        $profile = $studentUser->student;

        $studentIds = [$studentId];
        try {
            $studentIds[] = new \MongoDB\BSON\ObjectId($studentId);
        } catch (\Exception $e) {}

        // 1. Assessments
        $assessments = Assessment::whereIn('student_id', $studentIds)->with(['subjectRelation', 'teacher'])->orderBy('created_at', 'desc')->get();
        
        $subjects = $assessments->pluck('subject')->unique();
        if ($subjects->isEmpty()) {
            $subjects = collect([
                'Engineering Mathematics', 'Engineering Physics', 'Engineering Chemistry',
                'Computer Programming', 'Basic Electronics', 'Basic Electrical',
                'Engineering Mechanics', 'Engineering Graphics', 'Environmental Sciences'
            ]);
        }
        
        $totalObtainedAll = 0;
        $totalMaxAll = 0;
        
        $subjectAverages = [];
        foreach ($subjects as $subject) {
            $subjAssessments = $assessments->where('subject', $subject);
            if ($subjAssessments->count() > 0) {
                $obtained = 0;
                $max = 0;
                foreach ($subjAssessments as $ast) {
                    $obtained += $ast->obtained;
                    $max += $ast->max_possible;
                }
                
                $subjectAverages[$subject] = $max > 0 ? round(($obtained / $max) * 100, 1) : 'N/A';
            } else {
                $subjectAverages[$subject] = 'N/A';
            }
        }

        foreach ($assessments as $ast) {
            $totalObtainedAll += $ast->obtained;
            $totalMaxAll += $ast->max_possible;
        }

        $overallAverage = $totalMaxAll > 0 ? round(($totalObtainedAll / $totalMaxAll) * 100, 1) : 0;
        
        // Slow Learner detection logic (overall average below 40%)
        $status = "Normal Student";
        if ($assessments->count() > 0 && $overallAverage < 40) {
            $status = "Slow Learner";
        } elseif ($assessments->count() == 0) {
            $status = "No Marks Yet";
        }

        // 2. Attendance %
        $totalDays = Attendance::whereIn('student_id', $studentIds)->count();
        $presentDays = Attendance::whereIn('student_id', $studentIds)->where('status', 'present')->count();
        $attendancePercentage = ($totalDays > 0) ? round(($presentDays / $totalDays) * 100, 1) : 0;

        // 3. Remedial Tasks (only loaded if overall average is below 40% threshold)
        $tasks = RemedialTask::whereIn('student_id', $studentIds)->with(['subject', 'teacher', 'student'])->orderBy('created_at', 'desc')->get();
        if ($overallAverage >= 40) {
            $tasks = collect(); // Clear tasks as remedial is active only when student is a slow learner
        }
        $pendingTasksCount = $tasks->where('status', 'pending')->count();

        // 4. Feedback
        $feedbacks = Feedback::whereIn('student_id', $studentIds)->with('teacher')->orderBy('created_at', 'desc')->get();

        return [
            'user' => $studentUser,
            'profile' => $profile,
            'assessments' => $assessments,
            'subjectAverages' => $subjectAverages,
            'overallAverage' => $overallAverage,
            'status' => $status,
            'attendancePercentage' => $attendancePercentage,
            'totalDays' => $totalDays,
            'presentDays' => $presentDays,
            'tasks' => $tasks,
            'pendingTasksCount' => $pendingTasksCount,
            'feedbacks' => $feedbacks,
        ];
    }

    /**
     * Student Dashboard.
     */
    public function dashboard()
    {
        $metrics = $this->getStudentMetrics(auth()->id());

        $studentIds = [auth()->id()];
        try {
            $studentIds[] = new \MongoDB\BSON\ObjectId(auth()->id());
        } catch (\Exception $e) {}

        // Auto-populate / self-healing check: Ensure student has an AssignmentSubmission record for each of their class's assignments
        $classAssignments = Assignment::where('class_code', auth()->user()->class_code)->get();
        foreach ($classAssignments as $assignment) {
            $assignmentIds = [$assignment->id];
            try {
                $assignmentIds[] = new \MongoDB\BSON\ObjectId($assignment->id);
            } catch (\Exception $e) {}

            $exists = AssignmentSubmission::whereIn('assignment_id', $assignmentIds)
                ->whereIn('student_id', $studentIds)
                ->exists();

            if (!$exists) {
                AssignmentSubmission::create([
                    'assignment_id' => $assignment->id,
                    'student_id' => auth()->id(),
                    'status' => 'pending',
                    'file_url' => null,
                    'submitted_at' => null,
                ]);
            }
        }

        // Load class-wise assignments and map their submissions
        $assignments = Assignment::where('class_code', auth()->user()->class_code)
            ->with('teacher')
            ->orderBy('due_date', 'asc')
            ->get()
            ->map(function ($assignment) use ($studentIds) {
                $assignmentIds = [$assignment->id];
                try {
                    $assignmentIds[] = new \MongoDB\BSON\ObjectId($assignment->id);
                } catch (\Exception $e) {}

                $assignment->userSubmission = AssignmentSubmission::whereIn('assignment_id', $assignmentIds)
                    ->whereIn('student_id', $studentIds)
                    ->first();
                return $assignment;
            });

        // Split assignments into pending (no submission, pending, or rejected submission)
        $pendingAssignments = $assignments->filter(function ($assignment) {
            return is_null($assignment->userSubmission) 
                || $assignment->userSubmission->status === 'pending' 
                || $assignment->userSubmission->status === 'rejected';
        });

        // Load all remedial tasks and map their submissions
        $remedialTasks = RemedialTask::whereIn('student_id', $studentIds)
            ->with(['teacher', 'subject'])
            ->orderBy('due_date', 'asc')
            ->get()
            ->map(function ($task) use ($studentIds) {
                $taskIds = [$task->id];
                try {
                    $taskIds[] = new \MongoDB\BSON\ObjectId($task->id);
                } catch (\Exception $e) {}

                $task->userSubmission = RemedialTaskSubmission::whereIn('remedial_task_id', $taskIds)
                    ->whereIn('student_id', $studentIds)
                    ->first();
                return $task;
            });

        // Split remedial tasks into pending
        $pendingRemedials = $remedialTasks->filter(function ($task) {
            return is_null($task->userSubmission) || $task->userSubmission->status === 'rejected';
        });

        // Completed Work (Assignments/Remedials reviewed/submitted: status in submitted, accepted, rejected, completed)
        $completedAssignments = AssignmentSubmission::whereIn('student_id', $studentIds)
            ->whereIn('status', ['submitted', 'accepted', 'rejected', 'completed'])
            ->with('assignment.teacher')
            ->get();

        $completedRemedials = RemedialTaskSubmission::whereIn('student_id', $studentIds)
            ->whereIn('status', ['submitted', 'accepted', 'rejected', 'completed'])
            ->with('remedialTask.teacher')
            ->get();

        $completedWork = collect();
        foreach ($completedAssignments as $sub) {
            $completedWork->push([
                'type' => 'Assignment',
                'title' => $sub->assignment->title ?? 'Untitled Assignment',
                'score' => $sub->score,
                'max_score' => $sub->assignment->max_score ?? 100,
                'feedback' => $sub->feedback,
                'reviewed_at' => $sub->reviewed_at,
                'status' => $sub->status,
                'is_excellent' => $sub->isExcellent(),
                'needs_improvement' => $sub->needsImprovement(),
                'is_late' => $sub->isLate(),
                'file_url' => $sub->file_url
            ]);
        }
        foreach ($completedRemedials as $sub) {
            $completedWork->push([
                'type' => 'Remedial Task',
                'title' => $sub->remedialTask->title ?? 'Untitled Remedial Task',
                'score' => $sub->score,
                'max_score' => $sub->remedialTask->max_score ?? 100,
                'feedback' => $sub->feedback,
                'reviewed_at' => $sub->reviewed_at,
                'status' => $sub->status,
                'is_excellent' => $sub->isExcellent(),
                'needs_improvement' => $sub->needsImprovement(),
                'is_late' => $sub->isLate(),
                'file_url' => $sub->file_url
            ]);
        }

        $completedWork = $completedWork->sortByDesc('reviewed_at')->values();

        $data = array_merge($metrics, [
            'assignments' => $assignments,
            'pendingAssignments' => $pendingAssignments,
            'remedialTasks' => $remedialTasks,
            'pendingRemedials' => $pendingRemedials,
            'completedWork' => $completedWork,
        ]);

        return view('student.dashboard', $data);
    }

    /**
     * Show detailed marks page.
     */
    public function marks()
    {
        $metrics = $this->getStudentMetrics(auth()->id());
        return view('student.marks', $metrics);
    }

    /**
     * Show detailed attendance logs.
     */
    public function attendance()
    {
        $metrics = $this->getStudentMetrics(auth()->id());
        $attendanceLogs = Attendance::where('student_id', auth()->id())->orderBy('created_at', 'desc')->get();
        
        return view('student.attendance', array_merge($metrics, ['attendanceLogs' => $attendanceLogs]));
    }

    /**
     * Show remedial tasks list and status.
     */
    public function tasks()
    {
        $metrics = $this->getStudentMetrics(auth()->id());
        return view('student.tasks', $metrics);
    }

    /**
     * Mark a remedial task as completed.
     */
    public function completeTask($id)
    {
        $task = RemedialTask::where('id', $id)->where('student_id', auth()->id())->firstOrFail();
        
        $task->update([
            'status' => 'completed',
        ]);

        return redirect()->back()->with('success', 'Remedial task marked as completed!');
    }

    /**
     * Show feedback given by teachers.
     */
    public function feedback()
    {
        $metrics = $this->getStudentMetrics(auth()->id());
        return view('student.feedback', $metrics);
    }

    /**
     * Show remedial task details and solver form.
     */
    public function showTask($id)
    {
        $task = RemedialTask::where('id', $id)->where('student_id', auth()->id())->with(['subject', 'teacher', 'student'])->firstOrFail();
        $metrics = $this->getStudentMetrics(auth()->id());
        
        return view('student.tasks.show', array_merge($metrics, ['task' => $task]));
    }

    /**
     * Submit remedial task solution (text and optional PDF/JPEG/PNG).
     */
    public function submitTask(Request $request, $id)
    {
        $task = RemedialTask::where('id', $id)->where('student_id', auth()->id())->firstOrFail();

        $request->validate([
            'submission_text' => 'nullable|string|max:5000',
            'submission_file' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:5120', // max 5MB
        ]);

        if (!$request->filled('submission_text') && !$request->hasFile('submission_file')) {
            return redirect()->back()->withErrors(['submission' => 'Please type a written solution or upload a file.'])->withInput();
        }

        if ($request->hasFile('submission_file')) {
            $path = $request->file('submission_file')->store('task_submissions', 'public');
            $task->submission_file = $path;
        }

        $task->submission_text = $request->submission_text;
        $task->status = 'completed';
        $task->completed_at = now();
        $task->save();

        return redirect()->route('student.tasks')->with('success', 'Remedial task submitted successfully and marked as completed!');
    }

    /**
     * Submit/upload a file for an assignment.
     */
    public function submitAssignment(Request $request, $id)
    {
        $assignment = Assignment::findOrFail($id);

        $request->validate([
            'submission_file' => 'required|file|mimes:pdf,doc,docx,jpeg,png,jpg,gif|max:10240', // max 10MB
        ]);

        $file = $request->file('submission_file');

        // Create directory if not exists
        $destinationPath = public_path('uploads/assignments');
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }

        $filename = time() . '_' . $file->getClientOriginalName();
        $file->move($destinationPath, $filename);
        $localPath = 'uploads/assignments/' . $filename;

        // Check if there is an existing submission
        $submission = AssignmentSubmission::where('assignment_id', $assignment->id)
            ->where('student_id', auth()->id())
            ->first();

        if ($submission) {
            // Check if it is already reviewed and accepted/completed
            if (in_array($submission->status, ['accepted', 'completed'])) {
                return redirect()->back()->withErrors(['submission_file' => 'This assignment has already been reviewed and accepted.']);
            }

            // Delete old file if exists
            if ($submission->file_url && file_exists(public_path($submission->file_url))) {
                @unlink(public_path($submission->file_url));
            }

            // Update submission
            $submission->update([
                'file_url' => $localPath,
                'public_id' => null,
                'status' => 'submitted',
                'submitted_at' => now(),
            ]);
        } else {
            // Create new submission
            AssignmentSubmission::create([
                'assignment_id' => $assignment->id,
                'student_id' => auth()->id(),
                'file_url' => $localPath,
                'public_id' => null,
                'status' => 'submitted',
                'submitted_at' => now(),
            ]);
        }

        return redirect()->route('student.dashboard')->with('success', 'Assignment file submitted successfully!');
    }

    /**
     * Submit/upload a file for a remedial task.
     */
    public function submitRemedial(Request $request, $id)
    {
        $task = RemedialTask::where('id', $id)->where('student_id', auth()->id())->firstOrFail();

        $request->validate([
            'submission_file' => 'required|file|mimes:pdf,doc,docx,jpeg,png,jpg,gif|max:10240', // max 10MB
        ]);

        $file = $request->file('submission_file');

        // Create directory if not exists
        $destinationPath = public_path('uploads/remedials');
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }

        $filename = time() . '_' . $file->getClientOriginalName();
        $file->move($destinationPath, $filename);
        $localPath = 'uploads/remedials/' . $filename;

        // Check if there is an existing submission
        $submission = RemedialTaskSubmission::where('remedial_task_id', $task->id)
            ->where('student_id', auth()->id())
            ->first();

        if ($submission) {
            if (in_array($submission->status, ['accepted', 'completed'])) {
                return redirect()->back()->withErrors(['submission_file' => 'This remedial task has already been reviewed and completed.']);
            }

            // Delete old file if exists
            if ($submission->file_url && file_exists(public_path($submission->file_url))) {
                @unlink(public_path($submission->file_url));
            }

            // Update
            $submission->update([
                'file_url' => $localPath,
                'public_id' => null,
                'status' => 'submitted',
                'submitted_at' => now(),
            ]);
        } else {
            // Create
            RemedialTaskSubmission::create([
                'remedial_task_id' => $task->id,
                'student_id' => auth()->id(),
                'file_url' => $localPath,
                'public_id' => null,
                'status' => 'submitted',
                'submitted_at' => now(),
            ]);
        }

        // Also update task main status to indicate file has been submitted
        $task->update([
            'status' => 'completed',
            'completed_at' => now()
        ]);

        return redirect()->route('student.dashboard')->with('success', 'Remedial task file submitted successfully!');
    }
}
