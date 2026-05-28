<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AssignmentSubmission extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';
    protected $collection = 'assignment_submissions';

    protected $fillable = [
        'assignment_id',
        'student_id',
        'file_url',
        'public_id',
        'status', // pending, submitted, accepted, rejected, completed
        'score',
        'feedback',
        'teacher_notes', // private notes visible only to the teacher
        'submitted_at',
        'reviewed_at',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'reviewed_at' => 'datetime',
    ];

    /**
     * Get the assignment details.
     */
    public function assignment()
    {
        return $this->belongsTo(Assignment::class, 'assignment_id');
    }

    /**
     * Get the student who submitted the assignment.
     */
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    /**
     * Auto-detect if submission was late based on the assignment's due date.
     */
    public function isLate(): bool
    {
        if (!$this->assignment || !$this->submitted_at || !$this->assignment->due_date) {
            return false;
        }
        return $this->submitted_at->gt($this->assignment->due_date);
    }

    /**
     * Auto-detect if this submission represents excellent work (score >= 80%).
     */
    public function isExcellent(): bool
    {
        if (is_null($this->score) || !$this->assignment) {
            return false;
        }
        $max = $this->assignment->max_score ?? 100;
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
        if (is_null($this->score) || !$this->assignment) {
            return false;
        }
        $max = $this->assignment->max_score ?? 100;
        if ($max <= 0) return false;
        return ($this->score / $max) < 0.5;
    }
}
