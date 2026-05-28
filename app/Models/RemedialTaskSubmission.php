<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RemedialTaskSubmission extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';
    protected $collection = 'remedial_task_submissions';

    protected $fillable = [
        'remedial_task_id',
        'student_id',
        'file_url',
        'public_id',
        'status', // pending, submitted, accepted, rejected, completed
        'feedback',
        'score',
        'teacher_notes', // private notes visible only to the teacher
        'submitted_at',
        'reviewed_at',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'reviewed_at' => 'datetime',
    ];

    /**
     * Get the parent remedial task.
     */
    public function remedialTask()
    {
        return $this->belongsTo(RemedialTask::class, 'remedial_task_id');
    }

    /**
     * Get the student who made the submission.
     */
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    /**
     * Auto-detect if submission was late based on remedial task due date.
     */
    public function isLate(): bool
    {
        if (!$this->remedialTask || !$this->submitted_at || !$this->remedialTask->due_date) {
            return false;
        }
        return $this->submitted_at->gt($this->remedialTask->due_date);
    }

    /**
     * Auto-detect if this submission represents excellent work (score >= 80%).
     */
    public function isExcellent(): bool
    {
        if (is_null($this->score) || !$this->remedialTask) {
            return false;
        }
        $max = $this->remedialTask->max_score ?? 100;
        if ($max <= 0) return false;
        return ($this->score / $max) >= 0.8;
    }

    /**
     * Auto-detect if this submission needs improvement (score < 50% or rejected).
     */
    public function needsImprovement(): bool
    {
        if ($this->status === 'rejected') {
            return true;
        }
        if (is_null($this->score) || !$this->remedialTask) {
            return false;
        }
        $max = $this->remedialTask->max_score ?? 100;
        if ($max <= 0) return false;
        return ($this->score / $max) < 0.5;
    }
}
