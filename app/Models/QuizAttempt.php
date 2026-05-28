<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class QuizAttempt extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';
    protected $collection = 'quiz_attempts';

    protected $fillable = [
        'quiz_id',
        'student_id',
        'status', // 'in_progress', 'submitted', 'terminated'
        'started_at',
        'submitted_at',
        'score',
        'max_score',
        'answers', // array of embedded answers: ['question_id' => ..., 'selected_option' => ..., 'is_correct' => ...]
        'tab_switch_count',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'submitted_at' => 'datetime',
        'answers' => 'array',
        'score' => 'double',
        'max_score' => 'double',
        'tab_switch_count' => 'integer',
    ];

    /**
     * Get the parent quiz.
     */
    public function quiz()
    {
        return $this->belongsTo(Quiz::class, 'quiz_id');
    }

    /**
     * Get the student.
     */
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    /**
     * Get all tab switch logs.
     */
    public function tabSwitchLogs()
    {
        return $this->hasMany(TabSwitchLog::class, 'quiz_attempt_id');
    }

    /**
     * Check if attempt represents excellent work (score >= 80%).
     */
    public function isExcellent(): bool
    {
        if (is_null($this->score) || !$this->max_score) return false;
        return ($this->score / $this->max_score) >= 0.8;
    }

    /**
     * Check if attempt needs improvement (score < 50% or terminated).
     */
    public function needsImprovement(): bool
    {
        if ($this->status === 'terminated') return true;
        if (is_null($this->score) || !$this->max_score) return false;
        return ($this->score / $this->max_score) < 0.5;
    }

    /**
     * Check if attempt is late.
     */
    public function isLate(): bool
    {
        if (!$this->quiz || !$this->submitted_at || !$this->quiz->deadline) {
            return false;
        }
        return $this->submitted_at->gt($this->quiz->deadline);
    }
}
