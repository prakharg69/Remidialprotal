<?php

// Bootstrap Laravel
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Quiz;
use App\Models\Assessment;
use App\Models\QuizAttempt;

echo "=== SIMULATING QUIZ STUDENT CONTROLLER INDEX ===\n\n";

$prakhar = User::where('email', 'prakhar@gmail.com')->first();
if (!$prakhar) {
    echo "❌ Prakhar not found.\n";
    exit;
}

// Log Prakhar in programmatically for simulation
auth()->login($prakhar);

// Exact code from getStudentType()
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
$studentType = ($assessments->count() > 0 && $overallAverage < 40) ? 'remedial' : 'normal';

echo "Calculated Student Type: " . $studentType . "\n";

// Exact code from index()
$classCode = auth()->user()->class_code;
echo "Auth User Class Code: " . ($classCode ?? 'NULL') . "\n";

$quizzesQuery = Quiz::where('class_code', $classCode)
    ->where('student_type', $studentType);

echo "SQL/MongoDB Query Criteria: class_code = '$classCode', student_type = '$studentType'\n";

$quizzes = $quizzesQuery->get();

echo "Quizzes Found count: " . $quizzes->count() . "\n";
foreach ($quizzes as $index => $q) {
    echo "[$index] Title: " . $q->title . "\n";
    echo "    Subject relation exists? " . ($q->subjectRelation ? "YES (" . $q->subjectRelation->name . ")" : "NO") . "\n";
    echo "    Teacher relation exists? " . ($q->teacher ? "YES (" . $q->teacher->name . ")" : "NO") . "\n";
}

echo "\n=== END OF SIMULATION ===\n";
