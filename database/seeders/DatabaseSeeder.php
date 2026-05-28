<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Student;
use App\Models\Assessment;
use App\Models\Attendance;
use App\Models\RemedialTask;
use App\Models\Feedback;
use App\Models\Subject;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Clear existing data in MongoDB Atlas
        User::truncate();
        Student::truncate();
        Subject::truncate();
        \App\Models\ClassSection::truncate();
        Assessment::truncate();
        Attendance::truncate();
        RemedialTask::truncate();
        Feedback::truncate();

        // 2. Seed Admin
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);

        // 3. Seed Teachers
        $teacher1 = User::create([
            'name' => 'Rahul Sharma',
            'email' => 'rahul@gmail.com',
            'password' => Hash::make('rahul'),
            'role' => 'teacher',
            'subjects' => ['CSE204', 'CSE202'],
        ]);

        $teacher2 = User::create([
            'name' => 'Priya Verma',
            'email' => 'priya@gmail.com',
            'password' => Hash::make('priya'),
            'role' => 'teacher',
            'subjects' => ['CSE206', 'CSE302'],
        ]);

        // 4. Seed Students (Total 25 students, 5 per class)
        $studentsData = [
            [
                'name' => 'Prakhar',
                'email' => 'prakhar@gmail.com',
                'password' => 'prakhar',
                'roll_number' => '101',
                'phone' => '9876543210',
                'address' => 'Dehradun, India',
            ],
            [
                'name' => 'Riya',
                'email' => 'riya@gmail.com',
                'password' => 'riya',
                'roll_number' => '102',
                'phone' => '8765432109',
                'address' => 'Delhi, India',
            ],
            [
                'name' => 'Aman',
                'email' => 'aman@gmail.com',
                'password' => 'aman',
                'roll_number' => '103',
                'phone' => '7654321098',
                'address' => 'Noida, India',
            ],
        ];

        for ($i = 4; $i <= 25; $i++) {
            $studentsData[] = [
                'name' => 'Student ' . $i,
                'email' => 'student' . $i . '@gmail.com',
                'password' => 'student' . $i,
                'roll_number' => (string)(100 + $i),
                'phone' => '98765432' . str_pad($i, 2, '0', STR_PAD_LEFT),
                'address' => 'City ' . $i . ', India',
            ];
        }

        $classes = [
            'BCA-3A' => 'BCA 3rd Year (Section A)',
            'BCA-3B' => 'BCA 3rd Year (Section B)',
            'BCA-3C' => 'BCA 3rd Year (Section C)',
            'BCA-3D' => 'BCA 3rd Year (Section D)',
            'BCA-3E' => 'BCA 3rd Year (Section E)',
        ];
        foreach ($classes as $code => $name) {
            \App\Models\ClassSection::create([
                'code' => $code,
                'name' => $name,
            ]);
        }
        $classCodesList = array_keys($classes);

        foreach ($studentsData as $index => $st) {
            $classIndex = floor($index / 5);
            $classCode = $classCodesList[$classIndex] ?? 'BCA-3E';
            $className = $classes[$classCode] ?? 'BCA 3rd Year (Section E)';

            $user = User::create([
                'name' => $st['name'],
                'email' => $st['email'],
                'password' => Hash::make($st['password']),
                'role' => 'student',
                'class_code' => $classCode,
            ]);

            Student::create([
                'user_id' => $user->id,
                'class_code' => $classCode,
                'class' => $className,
                'roll_number' => $st['roll_number'],
                'phone' => $st['phone'],
                'address' => $st['address'],
            ]);

            // Seed exactly 20 subjects with academic code structure (MTH101, PHY102, EEE106, CSE202, etc.)
            if (!isset($createdSubjects)) {
                $subjectsList = [
                    ['code' => 'MTH101', 'name' => 'Engineering Mathematics I'],
                    ['code' => 'PHY102', 'name' => 'Engineering Physics'],
                    ['code' => 'CHM103', 'name' => 'Engineering Chemistry'],
                    ['code' => 'CSE104', 'name' => 'Computer Programming'],
                    ['code' => 'ECE105', 'name' => 'Basic Electronics'],
                    ['code' => 'EEE106', 'name' => 'Basic Electrical'],
                    ['code' => 'MEC107', 'name' => 'Engineering Mechanics'],
                    ['code' => 'GRA108', 'name' => 'Engineering Graphics'],
                    ['code' => 'EVS109', 'name' => 'Environmental Sciences'],
                    ['code' => 'MTH201', 'name' => 'Engineering Mathematics II'],
                    ['code' => 'CSE202', 'name' => 'Data Structures'],
                    ['code' => 'CSE203', 'name' => 'Object Oriented Programming'],
                    ['code' => 'CSE204', 'name' => 'Database Management Systems'],
                    ['code' => 'CSE205', 'name' => 'Operating Systems'],
                    ['code' => 'CSE206', 'name' => 'Computer Networks'],
                    ['code' => 'MEC207', 'name' => 'Thermodynamics'],
                    ['code' => 'ECE208', 'name' => 'Digital Electronics'],
                    ['code' => 'CSE301', 'name' => 'Software Engineering'],
                    ['code' => 'CSE302', 'name' => 'Web Development'],
                    ['code' => 'CSE303', 'name' => 'Artificial Intelligence'],
                ];

                $createdSubjects = [];
                foreach ($subjectsList as $s) {
                    $createdSubjects[] = Subject::create([
                        'code' => $s['code'],
                        'name' => $s['name'],
                        'ca1_max' => 30,
                        'ca2_max' => 30,
                        'end_term_max' => 40,
                    ]);
                }
            }



            // Seed some initial attendance logs
            Attendance::create([
                'student_id' => $user->id,
                'date' => date('Y-m-d', strtotime('-2 days')),
                'status' => 'present',
            ]);
            Attendance::create([
                'student_id' => $user->id,
                'date' => date('Y-m-d', strtotime('-1 days')),
                'status' => ($st['name'] === 'Aman') ? 'absent' : ((rand(1, 10) > 8) ? 'absent' : 'present'),
            ]);
            Attendance::create([
                'student_id' => $user->id,
                'date' => date('Y-m-d'),
                'status' => 'present',
            ]);
        }

        // 5. Seed Test Assignments & Remedial Tasks for prakhar@gmail.com
        $prakhar = User::where('email', 'prakhar@gmail.com')->first();
        $teacher = User::where('email', 'rahul@gmail.com')->first();
        $dbSubject = Subject::where('code', 'CSE204')->first(); // Database Management Systems

        if ($prakhar && $teacher) {
            // Seed a standard assignment
            \App\Models\Assignment::create([
                'teacher_id' => $teacher->id,
                'title' => 'SQL DB Schema & CRUD Triggers Assignment',
                'description' => "Write SQL queries to design a school administration database schema and implement standard CRUD triggers. Submit your completed PDF or SQL file.\n\nInstructions:\n1. Define tables for Students, Teachers, Classes, and Grades.\n2. Add indexes and foreign key constraints.\n3. Implement a trigger to automatically log changes in grades.",
                'subject' => $dbSubject ? $dbSubject->name : 'Database Management Systems',
                'class_code' => 'BCA-3A',
                'due_date' => now()->addDays(5),
                'max_score' => 100,
            ]);

            // Seed a past-due assignment to test Late Submission detection
            \App\Models\Assignment::create([
                'teacher_id' => $teacher->id,
                'title' => 'Algorithms & Data Structures CA1 Assignment',
                'description' => 'Write a clean PHP or Java program implementing Binary Search Trees, including insertion, deletion, and in-order/pre-order traversal algorithms. This was due yesterday.',
                'subject' => 'Data Structures',
                'class_code' => 'BCA-3A',
                'due_date' => now()->subDay(),
                'max_score' => 50,
            ]);

            // Seed a private remedial task for Prakhar
            \App\Models\RemedialTask::create([
                'teacher_id' => $teacher->id,
                'student_id' => $prakhar->id,
                'subject_id' => $dbSubject ? $dbSubject->id : null,
                'title' => 'Remedial Worksheet: Mastering SQL Joins',
                'description' => 'Write a comprehensive review and solve questions 1-5 from Chapter 4 on INNER, LEFT, RIGHT, and FULL OUTER joins. Complete this worksheet to improve your understanding of databases.',
                'due_date' => now()->addDays(3),
                'max_score' => 50,
                'status' => 'pending',
            ]);

            // Seed student assessment scores to classify them
            // Prakhar (average < 40%) -> remedial
            \App\Models\Assessment::create([
                'student_id' => $prakhar->id,
                'subject_id' => $dbSubject ? $dbSubject->id : null,
                'subject' => $dbSubject ? $dbSubject->name : 'Database Management Systems',
                'teacher_id' => $teacher->id,
                'ca1' => 8,
                'ca2' => 3,
                'end_term' => null,
            ]);

            // Riya (average >= 40%) -> normal
            $riya = User::where('email', 'riya@gmail.com')->first();
            if ($riya) {
                \App\Models\Assessment::create([
                    'student_id' => $riya->id,
                    'subject_id' => $dbSubject ? $dbSubject->id : null,
                    'subject' => $dbSubject ? $dbSubject->name : 'Database Management Systems',
                    'teacher_id' => $teacher->id,
                    'ca1' => 28,
                    'ca2' => 27,
                    'end_term' => null,
                ]);
            }

            // Seed Remedial Quiz for class BCA-3A
            \App\Models\Quiz::create([
                'teacher_id' => $teacher->id,
                'subject_id' => $dbSubject ? $dbSubject->id : null,
                'class_code' => 'BCA-3A',
                'student_type' => 'remedial',
                'title' => 'Remedial Quiz: Database Fundamentals',
                'instructions' => 'This is a secure support assessment to check your basic query knowledge. Do not leave the active window or switch tabs.',
                'duration' => 10,
                'deadline' => now()->addDays(2),
                'questions' => [
                    [
                        'id' => 'q1',
                        'question_text' => 'Which SQL clause is used to filter records based on a specified condition?',
                        'options' => ['GROUP BY', 'WHERE', 'ORDER BY', 'SELECT'],
                        'correct_option' => 1
                    ],
                    [
                        'id' => 'q2',
                        'question_text' => 'Which of the following represents a primary key constraint uniqueness rule?',
                        'options' => ['Must allow duplicate values', 'Must be NULL', 'Must be unique and not NULL', 'Can be any string value'],
                        'correct_option' => 2
                    ],
                    [
                        'id' => 'q3',
                        'question_text' => 'What is the full form of SQL?',
                        'options' => ['Structured Query Language', 'Simple Queue List', 'System Query Logic', 'Standard Query Layout'],
                        'correct_option' => 0
                    ]
                ]
            ]);

            // Seed Normal Quiz for class BCA-3A
            \App\Models\Quiz::create([
                'teacher_id' => $teacher->id,
                'subject_id' => $dbSubject ? $dbSubject->id : null,
                'class_code' => 'BCA-3A',
                'student_type' => 'normal',
                'title' => 'Standard Quiz: Advanced DB Operations',
                'instructions' => 'This is the standard database MCQ exam. Close all other browser tabs before starting.',
                'duration' => 20,
                'deadline' => now()->addDays(2),
                'questions' => [
                    [
                        'id' => 'nq1',
                        'question_text' => 'Which database normalization form eliminates transitive functional dependencies?',
                        'options' => ['First Normal Form (1NF)', 'Second Normal Form (2NF)', 'Third Normal Form (3NF)', 'Boyce-Codd Normal Form (BCNF)'],
                        'correct_option' => 2
                    ],
                    [
                        'id' => 'nq2',
                        'question_text' => 'What does ACID stand for in database transaction management?',
                        'options' => [
                            'Atomicity, Consistency, Isolation, Durability',
                            'Access, Control, Index, Data',
                            'Algorithm, Compiler, Interpreter, Debugger',
                            'Automated, Cached, Indexed, Distributed'
                        ],
                        'correct_option' => 0
                    ]
                ]
            ]);
        }
    }
}
