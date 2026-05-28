<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;

// Redirect root based on auth status
Route::get('/', function () {
    if (auth()->check()) {
        switch (auth()->user()->role) {
            case 'admin':
                return redirect('/admin/dashboard');
            case 'teacher':
                return redirect('/teacher/dashboard');
            case 'student':
                return redirect('/student/dashboard');
        }
    }
    return redirect('/login');
});

// Generic dashboard redirect
Route::get('/dashboard', function () {
    if (auth()->check()) {
        switch (auth()->user()->role) {
            case 'admin':
                return redirect('/admin/dashboard');
            case 'teacher':
                return redirect('/teacher/dashboard');
            case 'student':
                return redirect('/student/dashboard');
        }
    }
    return redirect('/login');
})->middleware(['auth'])->name('dashboard');

// Admin Routes (Role: admin)
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // Teacher CRUD
    Route::get('/teachers', [AdminController::class, 'teachers'])->name('teachers');
    Route::get('/teachers/create', [AdminController::class, 'createTeacher'])->name('teachers.create');
    Route::post('/teachers/store', [AdminController::class, 'storeTeacher'])->name('teachers.store');
    Route::get('/teachers/{id}/edit', [AdminController::class, 'editTeacher'])->name('teachers.edit');
    Route::put('/teachers/{id}/update', [AdminController::class, 'updateTeacher'])->name('teachers.update');
    Route::delete('/teachers/{id}', [AdminController::class, 'destroyTeacher'])->name('teachers.destroy');
    
    // Student CRUD
    Route::get('/students', [AdminController::class, 'students'])->name('students');
    Route::get('/students/create', [AdminController::class, 'createStudent'])->name('students.create');
    Route::post('/students/store', [AdminController::class, 'storeStudent'])->name('students.store');
    Route::get('/students/{id}/edit', [AdminController::class, 'editStudent'])->name('students.edit');
    Route::put('/students/{id}/update', [AdminController::class, 'updateStudent'])->name('students.update');
    Route::delete('/students/{id}', [AdminController::class, 'destroyStudent'])->name('students.destroy');
    
    // Class CRUD
    Route::get('/classes', [AdminController::class, 'classes'])->name('classes');
    Route::post('/classes/store', [AdminController::class, 'storeClass'])->name('classes.store');
    Route::delete('/classes/{id}', [AdminController::class, 'destroyClass'])->name('classes.destroy');
    
    // Subject CRUD
    Route::get('/subjects', [AdminController::class, 'subjects'])->name('subjects');
    Route::post('/subjects/store', [AdminController::class, 'storeSubject'])->name('subjects.store');
    Route::delete('/subjects/{id}', [AdminController::class, 'destroySubject'])->name('subjects.destroy');

    // Other Overviews
    Route::get('/assessments', [AdminController::class, 'assessments'])->name('assessments');
    Route::get('/attendance', [AdminController::class, 'attendance'])->name('attendance');
    Route::get('/tasks', [AdminController::class, 'tasks'])->name('tasks');
    Route::get('/feedback', [AdminController::class, 'feedback'])->name('feedback');
});

// Teacher Routes (Role: teacher)
Route::middleware(['auth', 'role:teacher'])->prefix('teacher')->name('teacher.')->group(function () {
    Route::get('/dashboard', [TeacherController::class, 'dashboard'])->name('dashboard');
    
    // Assessments
    Route::get('/assessments', [TeacherController::class, 'assessments'])->name('assessments');
    Route::post('/assessments/store', [TeacherController::class, 'storeAssessment'])->name('assessments.store');
    Route::get('/assessments/{id}/edit', [TeacherController::class, 'editAssessment'])->name('assessments.edit');
    Route::put('/assessments/{id}/update', [TeacherController::class, 'updateAssessment'])->name('assessments.update');
    Route::delete('/assessments/{id}', [TeacherController::class, 'destroyAssessment'])->name('assessments.destroy');
    
    // Attendance
    Route::get('/attendance', [TeacherController::class, 'attendance'])->name('attendance');
    Route::post('/attendance/store', [TeacherController::class, 'storeAttendance'])->name('attendance.store');
    
    // Remedial Tasks
    Route::get('/tasks', [TeacherController::class, 'tasks'])->name('tasks');
    Route::post('/tasks/store', [TeacherController::class, 'storeTask'])->name('tasks.store');

    // Assignments Creation & Overview
    Route::get('/assignments', [TeacherController::class, 'assignments'])->name('assignments');
    Route::post('/assignments/store', [TeacherController::class, 'storeAssignment'])->name('assignments.store');

    // Submissions & Review Panels
    Route::get('/assignments/submissions', [TeacherController::class, 'assignmentSubmissions'])->name('assignments.submissions');
    Route::post('/assignments/submissions/{id}/review', [TeacherController::class, 'reviewAssignmentSubmission'])->name('assignments.review');
    
    Route::get('/remedials/submissions', [TeacherController::class, 'remedialSubmissions'])->name('remedials.submissions');
    Route::post('/remedials/submissions/{id}/review', [TeacherController::class, 'reviewRemedialSubmission'])->name('remedials.review');
    
    // Feedback
    Route::get('/feedback', [TeacherController::class, 'feedback'])->name('feedback');
    Route::post('/feedback/store', [TeacherController::class, 'storeFeedback'])->name('feedback.store');

    // Quiz Management
    Route::get('/quizzes', [\App\Http\Controllers\QuizTeacherController::class, 'index'])->name('quizzes.index');
    Route::post('/quizzes/store', [\App\Http\Controllers\QuizTeacherController::class, 'store'])->name('quizzes.store');
    Route::get('/quizzes/attempts', [\App\Http\Controllers\QuizTeacherController::class, 'attempts'])->name('quizzes.attempts');
    Route::get('/quizzes/attempts/{id}/violations', [\App\Http\Controllers\QuizTeacherController::class, 'violations'])->name('quizzes.violations');
});

// Student Routes (Role: student)
Route::middleware(['auth', 'role:student'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', [StudentController::class, 'dashboard'])->name('dashboard');
    Route::get('/marks', [StudentController::class, 'marks'])->name('marks');
    Route::get('/attendance', [StudentController::class, 'attendance'])->name('attendance');
    Route::get('/tasks', [StudentController::class, 'tasks'])->name('tasks');
    Route::get('/roadmap', [StudentController::class, 'roadmap'])->name('roadmap');
    Route::get('/tasks/{id}', [StudentController::class, 'showTask'])->name('tasks.show');
    Route::post('/tasks/{id}/submit', [StudentController::class, 'submitTask'])->name('tasks.submit');
    Route::post('/tasks/{id}/complete', [StudentController::class, 'completeTask'])->name('tasks.complete');
    
    // Secure Upload Submissions
    Route::post('/assignments/{id}/submit', [StudentController::class, 'submitAssignment'])->name('assignments.submit');
    Route::post('/remedials/{id}/submit', [StudentController::class, 'submitRemedial'])->name('remedials.submit');
    
    Route::get('/feedback', [StudentController::class, 'feedback'])->name('feedback');

    // Quizzes Secure Portal
    Route::get('/quizzes', [\App\Http\Controllers\QuizStudentController::class, 'index'])->name('quizzes.index');
    Route::post('/quizzes/{id}/start', [\App\Http\Controllers\QuizStudentController::class, 'start'])->name('quizzes.start');
    Route::get('/quizzes/attempts/{id}', [\App\Http\Controllers\QuizStudentController::class, 'attempt'])->name('quizzes.attempt');
    Route::post('/quizzes/attempts/{id}/answer', [\App\Http\Controllers\QuizStudentController::class, 'saveAnswer'])->name('quizzes.saveAnswer');
    Route::post('/quizzes/attempts/{id}/tab-switch', [\App\Http\Controllers\QuizStudentController::class, 'logTabSwitch'])->name('quizzes.logTabSwitch');
    Route::post('/quizzes/attempts/{id}/submit', [\App\Http\Controllers\QuizStudentController::class, 'submit'])->name('quizzes.submit');
    Route::get('/quizzes/attempts/{id}/result', [\App\Http\Controllers\QuizStudentController::class, 'result'])->name('quizzes.result');
});

require __DIR__.'/auth.php';
