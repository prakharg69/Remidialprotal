<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Assessment;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\TabSwitchLog;
use Illuminate\Http\Request;
use Carbon\Carbon;

class QuizStudentController extends Controller
{
    /**
     * Determine student's type (normal vs remedial).
     */
    private function getStudentType()
    {
        $studentId = auth()->id();
        $studentIds = [$studentId];
        try {
            $studentIds[] = new \MongoDB\BSON\ObjectId($studentId);
        } catch (\Exception $e) {}

        $assessments = Assessment::whereIn('student_id', $studentIds)->get();
        $totalObtainedAll = 0;
        $totalMaxAll = 0;
        foreach ($assessments as $ast) {
            $totalObtainedAll += $ast->obtained;
            $totalMaxAll += $ast->max_possible;
        }

        $overallAverage = $totalMaxAll > 0 ? ($totalObtainedAll / $totalMaxAll) * 100 : 0;
        
        // Slow learner (average < 40%) is remedial
        if ($assessments->count() > 0 && $overallAverage < 40) {
            return 'remedial';
        }
        return 'normal';
    }

    /**
     * List all quizzes assigned to student.
     */
    public function index()
    {
        $studentType = $this->getStudentType();
        $classCode = auth()->user()->class_code;

        $studentIds = [auth()->id()];
        try {
            $studentIds[] = new \MongoDB\BSON\ObjectId(auth()->id());
        } catch (\Exception $e) {}

        // Fetch assigned quizzes
        $quizzes = Quiz::where('class_code', $classCode)
            ->where('student_type', $studentType)
            ->with(['subjectRelation', 'teacher'])
            ->orderBy('deadline', 'asc')
            ->get()
            ->map(function ($quiz) use ($studentIds) {
                $quizIds = [$quiz->id];
                try {
                    $quizIds[] = new \MongoDB\BSON\ObjectId($quiz->id);
                } catch (\Exception $e) {}

                // Map student attempt if exists
                $quiz->attempt = QuizAttempt::whereIn('quiz_id', $quizIds)
                    ->whereIn('student_id', $studentIds)
                    ->first();
                return $quiz;
            });

        return view('student.quizzes.index', compact('quizzes', 'studentType'));
    }

    /**
     * Start/Initialize a quiz attempt.
     */
    public function start(Request $request, $id)
    {
        $quiz = Quiz::findOrFail($id);
        $studentType = $this->getStudentType();
        $classCode = auth()->user()->class_code;

        // Validation checks
        if ($quiz->class_code !== $classCode || $quiz->student_type !== $studentType) {
            abort(403, 'Unauthorized actions.');
        }

        if (Carbon::now()->gt($quiz->deadline)) {
            return redirect()->back()->withErrors(['quiz' => 'This quiz has expired. The deadline was ' . $quiz->deadline->format('M d, Y h:i A')]);
        }

        $studentIds = [auth()->id()];
        try {
            $studentIds[] = new \MongoDB\BSON\ObjectId(auth()->id());
        } catch (\Exception $e) {}

        $quizIds = [$quiz->id];
        try {
            $quizIds[] = new \MongoDB\BSON\ObjectId($quiz->id);
        } catch (\Exception $e) {}

        // Fetch existing attempt
        $attempt = QuizAttempt::whereIn('quiz_id', $quizIds)
            ->whereIn('student_id', $studentIds)
            ->first();

        if ($attempt) {
            if ($attempt->status !== 'in_progress') {
                return redirect()->route('student.quizzes.result', $attempt->id);
            }
            return redirect()->route('student.quizzes.attempt', $attempt->id);
        }

        // Initialize empty answers array
        $answers = [];
        foreach ($quiz->questions as $q) {
            $answers[] = [
                'question_id' => $q['id'],
                'selected_option' => null,
                'is_correct' => false,
            ];
        }

        $attempt = QuizAttempt::create([
            'quiz_id' => $quiz->id,
            'student_id' => auth()->id(),
            'status' => 'in_progress',
            'started_at' => Carbon::now(),
            'submitted_at' => null,
            'score' => 0.0,
            'max_score' => doubleval(count($quiz->questions)),
            'answers' => $answers,
            'tab_switch_count' => 0,
        ]);

        return redirect()->route('student.quizzes.attempt', $attempt->id);
    }

    /**
     * Active quiz taking view.
     */
    public function attempt($id)
    {
        $attempt = QuizAttempt::findOrFail($id);

        if (strval($attempt->student_id) !== strval(auth()->id())) {
            abort(403, 'Unauthorized.');
        }

        if ($attempt->status !== 'in_progress') {
            return redirect()->route('student.quizzes.result', $attempt->id);
        }

        // Double check time limit self-healing check
        $quiz = $attempt->quiz;
        $timeSpentSeconds = Carbon::now()->diffInSeconds($attempt->started_at);
        $timeLimitSeconds = $quiz->duration * 60;
        
        // Auto-terminate / auto-evaluate on page reload if time has fully passed
        if ($timeSpentSeconds >= $timeLimitSeconds || Carbon::now()->gt($quiz->deadline)) {
            $this->evaluateAndSubmit($attempt);
            return redirect()->route('student.quizzes.result', $attempt->id)->with('error', 'Time ended. Quiz auto-submitted.');
        }

        $remainingSeconds = $timeLimitSeconds - $timeSpentSeconds;

        return view('student.quizzes.attempt', compact('attempt', 'quiz', 'remainingSeconds'));
    }

    /**
     * Save/update a single question answer via AJAX.
     */
    public function saveAnswer(Request $request, $id)
    {
        $attempt = QuizAttempt::findOrFail($id);

        if (strval($attempt->student_id) !== strval(auth()->id()) || $attempt->status !== 'in_progress') {
            return response()->json(['error' => 'Unauthorized or expired attempt.'], 403);
        }

        $request->validate([
            'question_id' => 'required|string',
            'selected_option' => 'nullable|integer|in:0,1,2,3',
        ]);

        $answers = $attempt->answers;
        foreach ($answers as &$ans) {
            if ($ans['question_id'] === $request->question_id) {
                $ans['selected_option'] = $request->selected_option !== null ? intval($request->selected_option) : null;
                
                // Real-time evaluation update
                $questionObj = collect($attempt->quiz->questions)->firstWhere('id', $request->question_id);
                $correctOpt = $questionObj['correct_option'] ?? -1;
                $ans['is_correct'] = ($ans['selected_option'] !== null && $ans['selected_option'] === $correctOpt);
                break;
            }
        }

        $attempt->answers = $answers;
        $attempt->save();

        return response()->json(['success' => true]);
    }

    /**
     * AJAX endpoint to log tab switches and auto-terminate if switches exceed 2.
     */
    public function logTabSwitch(Request $request, $id)
    {
        $attempt = QuizAttempt::findOrFail($id);

        if (strval($attempt->student_id) !== strval(auth()->id()) || $attempt->status !== 'in_progress') {
            return response()->json(['error' => 'Unauthorized or expired attempt.'], 403);
        }

        $newCount = $attempt->tab_switch_count + 1;
        $attempt->tab_switch_count = $newCount;
        $attempt->save();

        // Create log record
        TabSwitchLog::create([
            'quiz_attempt_id' => $attempt->id,
            'student_id' => auth()->id(),
            'switched_at' => Carbon::now(),
            'switch_number' => $newCount,
        ]);

        $terminated = false;
        if ($newCount >= 4) {
            // Auto terminate quiz after 3 warnings (on the 4th switch)
            $attempt->status = 'terminated';
            $this->evaluateAndSubmit($attempt);
            $terminated = true;
        }

        return response()->json([
            'success' => true,
            'switch_count' => $newCount,
            'terminated' => $terminated,
        ]);
    }

    /**
     * Submit action from student.
     */
    public function submit($id)
    {
        $attempt = QuizAttempt::findOrFail($id);

        if (strval($attempt->student_id) !== strval(auth()->id())) {
            abort(403, 'Unauthorized.');
        }

        if ($attempt->status === 'in_progress') {
            $attempt->status = 'submitted';
            $this->evaluateAndSubmit($attempt);
        }

        return redirect()->route('student.quizzes.result', $attempt->id)->with('success', 'Quiz submitted successfully!');
    }

    /**
     * Evaluate the quiz answers, score, and save.
     */
    private function evaluateAndSubmit(QuizAttempt $attempt)
    {
        $quiz = $attempt->quiz;
        $answers = $attempt->answers;
        $correctCount = 0;

        foreach ($answers as &$ans) {
            $questionObj = collect($quiz->questions)->firstWhere('id', $ans['question_id']);
            $correctOpt = $questionObj['correct_option'] ?? -1;
            
            if ($ans['selected_option'] !== null && intval($ans['selected_option']) === intval($correctOpt)) {
                $ans['is_correct'] = true;
                $correctCount++;
            } else {
                $ans['is_correct'] = false;
            }
        }

        $attempt->answers = $answers;
        $attempt->score = doubleval($correctCount);
        if ($attempt->status === 'in_progress') {
            $attempt->status = 'submitted';
        }
        $attempt->submitted_at = Carbon::now();
        $attempt->save();
    }

    /**
     * Result screen.
     */
    public function result($id)
    {
        $attempt = QuizAttempt::findOrFail($id);

        if (strval($attempt->student_id) !== strval(auth()->id())) {
            abort(403, 'Unauthorized.');
        }

        $quiz = $attempt->quiz;

        return view('student.quizzes.result', compact('attempt', 'quiz'));
    }
}
