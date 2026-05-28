<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Quiz extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';
    protected $collection = 'quizzes';

    protected $fillable = [
        'teacher_id',
        'subject_id',
        'class_code',
        'student_type', // 'normal', 'remedial'
        'title',
        'instructions',
        'duration', // minutes: 5, 10, 20, 40, 60
        'deadline', // datetime
        'questions', // array of MCQs
    ];

    protected $casts = [
        'deadline' => 'datetime',
        'questions' => 'array',
    ];

    /**
     * Get the teacher who created this quiz.
     */
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    /**
     * Get the subject.
     */
    public function subjectRelation()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    /**
     * Get all attempts.
     */
    public function attempts()
    {
        return $this->hasMany(QuizAttempt::class, 'quiz_id');
    }
}
