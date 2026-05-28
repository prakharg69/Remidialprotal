<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TabSwitchLog extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';
    protected $collection = 'tab_switch_logs';

    protected $fillable = [
        'quiz_attempt_id',
        'student_id',
        'switched_at',
        'switch_number',
    ];

    protected $casts = [
        'switched_at' => 'datetime',
        'switch_number' => 'integer',
    ];

    /**
     * Get the quiz attempt.
     */
    public function quizAttempt()
    {
        return $this->belongsTo(QuizAttempt::class, 'quiz_attempt_id');
    }

    /**
     * Get the student.
     */
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
}
