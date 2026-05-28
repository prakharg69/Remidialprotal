<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Student;
use App\Models\Assessment;
use App\Models\Attendance;
use App\Models\RemedialTask;
use App\Models\Feedback;
use App\Models\Subject;
use App\Models\ClassSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    /**
     * Display the Admin Dashboard with overall stats.
     */
    public function dashboard()
    {
        $totalStudents = User::where('role', 'student')->count();
        $totalTeachers = User::where('role', 'teacher')->count();
        $totalAssessments = Assessment::count();
        $totalTasks = RemedialTask::count();

        // Get recent feedback
        $recentFeedback = Feedback::with(['student', 'teacher'])->orderBy('created_at', 'desc')->take(5)->get();

        return view('admin.dashboard', compact('totalStudents', 'totalTeachers', 'totalAssessments', 'totalTasks', 'recentFeedback'));
    }

    /* =========================================================================
     * TEACHER CRUD
     * ========================================================================= */

    /**
     * List all teachers.
     */
    public function teachers()
    {
        $teachers = User::where('role', 'teacher')->orderBy('created_at', 'desc')->get();
        return view('admin.teachers.index', compact('teachers'));
    }

    /**
     * Show form to create a teacher.
     */
    public function createTeacher()
    {
        $subjects = Subject::orderBy('code', 'asc')->get();
        return view('admin.teachers.create', compact('subjects'));
    }

    /**
     * Store a newly created teacher.
     */
    public function storeTeacher(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'subjects' => 'nullable|array',
            'subjects.*' => 'string|exists:subjects,code',
        ]);

        // Automatic Password Logic: prefix of email before '@' symbol
        $emailPrefix = Str::before($request->email, '@');
        
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($emailPrefix),
            'role' => 'teacher',
            'subjects' => $request->input('subjects', []),
        ]);

        return redirect()->route('admin.teachers')->with('success', "Teacher created successfully. Default password is '{$emailPrefix}'.");
    }

    /**
     * Show form to edit a teacher.
     */
    public function editTeacher($id)
    {
        $teacher = User::findOrFail($id);
        $subjects = Subject::orderBy('code', 'asc')->get();
        return view('admin.teachers.edit', compact('teacher', 'subjects'));
    }

    /**
     * Update a teacher.
     */
    public function updateTeacher(Request $request, $id)
    {
        $teacher = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $teacher->id,
            'subjects' => 'nullable|array',
            'subjects.*' => 'string|exists:subjects,code',
        ]);

        $teacher->update([
            'name' => $request->name,
            'email' => $request->email,
            'subjects' => $request->input('subjects', []),
        ]);

        return redirect()->route('admin.teachers')->with('success', 'Teacher updated successfully.');
    }

    /**
     * Delete a teacher.
     */
    public function destroyTeacher($id)
    {
        $teacher = User::findOrFail($id);
        $teacher->delete();

        return redirect()->route('admin.teachers')->with('success', 'Teacher deleted successfully.');
    }

    /* =========================================================================
     * STUDENT CRUD
     * ========================================================================= */

    /**
     * List all students.
     */
    public function students()
    {
        $students = User::where('role', 'student')->with('student')->orderBy('created_at', 'desc')->get();

        // We can pre-calculate the averages and slow learner status
        foreach ($students as $student) {
            $assessments = Assessment::where('student_id', $student->id)->get();
            if ($assessments->count() > 0) {
                $average = $assessments->avg('total');
                $student->average_marks = round($average, 1);
                $student->status = ($average < 40) ? 'Slow Learner' : 'Normal Student';
            } else {
                $student->average_marks = null;
                $student->status = 'No Marks Yet';
            }
        }

        return view('admin.students.index', compact('students'));
    }

    /**
     * Show form to create a student.
     */
    public function createStudent()
    {
        $classes = ClassSection::orderBy('code', 'asc')->get();
        return view('admin.students.create', compact('classes'));
    }

    /**
     * Store a newly created student.
     */
    public function storeStudent(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'class_code' => 'required|string|exists:classes,code',
            'roll_number' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);

        $classSection = ClassSection::where('code', $request->class_code)->firstOrFail();

        // Automatic Password Logic: prefix of email before '@' symbol
        $emailPrefix = Str::before($request->email, '@');

        // 1. Create User
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($emailPrefix),
            'role' => 'student',
            'class_code' => $classSection->code,
        ]);

        // 2. Create Student Profile
        Student::create([
            'user_id' => $user->id,
            'class_code' => $classSection->code,
            'class' => $classSection->name,
            'roll_number' => $request->roll_number,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        return redirect()->route('admin.students')->with('success', "Student created successfully. Default password is '{$emailPrefix}'.");
    }

    /**
     * Show form to edit a student.
     */
    public function editStudent($id)
    {
        $studentUser = User::findOrFail($id);
        $profile = Student::where('user_id', $id)->first();
        $classes = ClassSection::orderBy('code', 'asc')->get();
        
        return view('admin.students.edit', compact('studentUser', 'profile', 'classes'));
    }

    /**
     * Update a student.
     */
    public function updateStudent(Request $request, $id)
    {
        $studentUser = User::findOrFail($id);
        $profile = Student::where('user_id', $id)->first();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $studentUser->id,
            'class_code' => 'required|string|exists:classes,code',
            'roll_number' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);

        $classSection = ClassSection::where('code', $request->class_code)->firstOrFail();

        // Update User Account
        $studentUser->update([
            'name' => $request->name,
            'email' => $request->email,
            'class_code' => $classSection->code,
        ]);

        // Update Student Profile
        if ($profile) {
            $profile->update([
                'class_code' => $classSection->code,
                'class' => $classSection->name,
                'roll_number' => $request->roll_number,
                'phone' => $request->phone,
                'address' => $request->address,
            ]);
        } else {
            Student::create([
                'user_id' => $studentUser->id,
                'class_code' => $classSection->code,
                'class' => $classSection->name,
                'roll_number' => $request->roll_number,
                'phone' => $request->phone,
                'address' => $request->address,
            ]);
        }

        return redirect()->route('admin.students')->with('success', 'Student updated successfully.');
    }

    /**
     * Delete a student.
     */
    public function destroyStudent($id)
    {
        // Delete User Account
        $studentUser = User::findOrFail($id);
        $studentUser->delete();

        // Delete Profile
        Student::where('user_id', $id)->delete();

        // Delete associated records
        Assessment::where('student_id', $id)->delete();
        Attendance::where('student_id', $id)->delete();
        RemedialTask::where('student_id', $id)->delete();
        Feedback::where('student_id', $id)->delete();

        return redirect()->route('admin.students')->with('success', 'Student deleted successfully.');
    }

    /* =========================================================================
     * READ-ONLY / GLOBAL OVERVIEWS FOR ADMIN
     * ========================================================================= */

    /**
     * View all assessments.
     */
    public function assessments()
    {
        $assessments = Assessment::with('student')->orderBy('created_at', 'desc')->get();
        return view('admin.assessments.index', compact('assessments'));
    }

    /**
     * View all attendance records.
     */
    public function attendance()
    {
        $attendance = Attendance::with('student')->orderBy('created_at', 'desc')->get();
        return view('admin.attendance.index', compact('attendance'));
    }

    /**
     * View all remedial tasks.
     */
    public function tasks()
    {
        $tasks = RemedialTask::with(['student', 'subject'])->orderBy('created_at', 'desc')->get();
        return view('admin.tasks.index', compact('tasks'));
    }

    /**
     * View all teacher feedbacks.
     */
    public function feedback()
    {
        $feedbacks = Feedback::with(['student', 'teacher'])->orderBy('created_at', 'desc')->get();
        return view('admin.feedback.index', compact('feedbacks'));
    }

    /* =========================================================================
     * CLASS CRUD
     * ========================================================================= */

    /**
     * List all class sections.
     */
    public function classes()
    {
        $classes = ClassSection::orderBy('code', 'asc')->get();
        
        // Count students in each class dynamically
        foreach ($classes as $class) {
            $class->student_count = User::where('role', 'student')
                ->where('class_code', $class->code)
                ->count();
        }

        return view('admin.classes.index', compact('classes'));
    }

    /**
     * Store a newly created class section.
     */
    public function storeClass(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:50|unique:classes,code',
            'name' => 'required|string|max:255',
        ]);

        ClassSection::create([
            'code' => strtoupper($request->code),
            'name' => $request->name,
        ]);

        return redirect()->route('admin.classes')->with('success', 'Class Section created successfully.');
    }

    /**
     * Delete a class section.
     */
    public function destroyClass($id)
    {
        $class = ClassSection::findOrFail($id);
        
        // Prevent deleting classes with students to protect integrity
        $studentCount = User::where('role', 'student')->where('class_code', $class->code)->count();
        if ($studentCount > 0) {
            return redirect()->route('admin.classes')->withErrors(['error' => "Cannot delete class '{$class->code}' as it has {$studentCount} registered student(s)."]);
        }

        $class->delete();

        return redirect()->route('admin.classes')->with('success', 'Class Section deleted successfully.');
    }

    /* =========================================================================
     * SUBJECT CRUD
     * ========================================================================= */

    /**
     * List all subjects.
     */
    public function subjects()
    {
        $subjects = Subject::orderBy('code', 'asc')->get();
        return view('admin.subjects.index', compact('subjects'));
    }

    /**
     * Store a newly created subject.
     */
    public function storeSubject(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:50|unique:subjects,code',
            'name' => 'required|string|max:255',
            'ca1_max' => 'required|integer|min:0|max:100',
            'ca2_max' => 'required|integer|min:0|max:100',
            'end_term_max' => 'required|integer|min:0|max:100',
        ]);

        Subject::create([
            'code' => strtoupper($request->code),
            'name' => $request->name,
            'ca1_max' => (int) $request->ca1_max,
            'ca2_max' => (int) $request->ca2_max,
            'end_term_max' => (int) $request->end_term_max,
        ]);

        return redirect()->route('admin.subjects')->with('success', 'Subject created successfully.');
    }

    /**
     * Delete a subject.
     */
    public function destroySubject($id)
    {
        $subject = Subject::findOrFail($id);
        $subject->delete();

        return redirect()->route('admin.subjects')->with('success', 'Subject deleted successfully.');
    }
}
