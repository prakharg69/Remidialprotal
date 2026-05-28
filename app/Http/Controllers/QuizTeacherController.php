<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Subject;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\TabSwitchLog;
use Illuminate\Http\Request;

class QuizTeacherController extends Controller
{
    /**
     * Display quizzes list and creation form.
     */
    public function index(Request $request)
    {
        $subjects = Subject::orderBy('code', 'asc')->get();
        
        // Fetch all unique class codes from student users
        $classCodes = User::where('role', 'student')->pluck('class_code')->unique()->filter()->sort()->values();

        // Query quizzes created by this teacher
        $teacherIds = [auth()->id()];
        try {
            $teacherIds[] = new \MongoDB\BSON\ObjectId(auth()->id());
        } catch (\Exception $e) {}

        $query = Quiz::whereIn('teacher_id', $teacherIds)->with(['subjectRelation', 'teacher']);

        // Filters
        if ($request->filled('subject_id')) {
            $subjectIds = [$request->subject_id];
            try {
                $subjectIds[] = new \MongoDB\BSON\ObjectId($request->subject_id);
            } catch (\Exception $e) {}
            $query->whereIn('subject_id', $subjectIds);
        }
        if ($request->filled('class_code')) {
            $query->where('class_code', $request->class_code);
        }
        if ($request->filled('student_type')) {
            $query->where('student_type', $request->student_type);
        }

        $quizzes = $query->orderBy('created_at', 'desc')->get();

        return view('teacher.quizzes.index', compact('quizzes', 'subjects', 'classCodes'));
    }

    /**
     * Store a new quiz.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'instructions' => 'nullable|string',
            'subject_id' => 'required|string',
            'class_code' => 'required|string',
            'student_type' => 'required|string|in:normal,remedial',
            'duration' => 'required|integer|in:5,10,20,40,60',
            'deadline_date' => 'required|date_format:Y-m-d',
            'deadline_time' => 'required|date_format:H:i',
            'questions' => 'required|array|min:1',
            'questions.*.question_text' => 'required|string|max:1000',
            'questions.*.options' => 'required|array|min:4|max:4',
            'questions.*.options.*' => 'required|string|max:500',
            'questions.*.correct_option' => 'required|integer|in:0,1,2,3',
        ]);

        $deadlineStr = $request->deadline_date . ' ' . $request->deadline_time . ':00';
        $deadline = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $deadlineStr);

        // Format embedded questions array
        $questions = [];
        foreach ($request->questions as $index => $q) {
            $questions[] = [
                'id' => (string) uniqid(),
                'question_text' => $q['question_text'],
                'options' => array_values($q['options']),
                'correct_option' => intval($q['correct_option']),
            ];
        }

        Quiz::create([
            'teacher_id' => auth()->id(),
            'subject_id' => $request->subject_id,
            'class_code' => $request->class_code,
            'student_type' => $request->student_type,
            'title' => $request->title,
            'instructions' => $request->instructions,
            'duration' => intval($request->duration),
            'deadline' => $deadline,
            'questions' => $questions,
        ]);

        return redirect()->route('teacher.quizzes.index')->with('success', 'Quiz created successfully!');
    }

    /**
     * View attempts and violations.
     */
    public function attempts(Request $request)
    {
        $subjects = Subject::orderBy('code', 'asc')->get();
        $classCodes = User::where('role', 'student')->pluck('class_code')->unique()->filter()->sort()->values();

        $teacherIds = [auth()->id()];
        try {
            $teacherIds[] = new \MongoDB\BSON\ObjectId(auth()->id());
        } catch (\Exception $e) {}

        // Fetch all quiz IDs for this teacher
        $quizIds = Quiz::whereIn('teacher_id', $teacherIds)->pluck('id')->toArray();
        
        $allQuizIds = [];
        foreach ($quizIds as $id) {
            $allQuizIds[] = $id;
            try {
                $allQuizIds[] = new \MongoDB\BSON\ObjectId($id);
            } catch (\Exception $e) {}
        }

        $query = QuizAttempt::whereIn('quiz_id', $allQuizIds)->with(['quiz.subjectRelation', 'student']);

        // Apply filters
        if ($request->filled('subject_id')) {
            $subjIds = [$request->subject_id];
            try { $subjIds[] = new \MongoDB\BSON\ObjectId($request->subject_id); } catch (\Exception $e) {}
            
            // Filter by subject_id on the relation
            $query->whereHas('quiz', function($q) use ($subjIds) {
                $q->whereIn('subject_id', $subjIds);
            });
        }
        if ($request->filled('class_code')) {
            $query->whereHas('quiz', function($q) use ($request) {
                $q->where('class_code', $request->class_code);
            });
        }
        if ($request->filled('student_type')) {
            $query->whereHas('quiz', function($q) use ($request) {
                $q->where('student_type', $request->student_type);
            });
        }

        $attempts = $query->orderBy('started_at', 'desc')->get();

        return view('teacher.quizzes.attempts', compact('attempts', 'subjects', 'classCodes'));
    }

    /**
     * View violations popup.
     */
    public function violations($id)
    {
        $attemptIds = [$id];
        try {
            $attemptIds[] = new \MongoDB\BSON\ObjectId($id);
        } catch (\Exception $e) {}

        $logs = TabSwitchLog::whereIn('quiz_attempt_id', $attemptIds)
            ->orderBy('switched_at', 'asc')
            ->get()
            ->map(function ($log) {
                return [
                    'switch_number' => $log->switch_number,
                    'switched_at' => $log->switched_at ? $log->switched_at->format('M d, Y h:i:s A') : 'N/A',
                ];
            });

        return response()->json($logs);
    }
}
