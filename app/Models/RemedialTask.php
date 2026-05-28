<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RemedialTask extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';
    protected $collection = 'remedial_tasks';

    protected $fillable = [
        'student_id',      // references user_id of the student
        'subject_id',      // references subject_id of the Subject model
        'teacher_id',      // references user_id of the teacher who assigned it
        'title',
        'description',
        'due_date',
        'max_score',
        'status',          // 'pending', 'completed'
        'submission_text', // student written solution
        'submission_file', // student uploaded file path (pdf, jpeg, png)
        'completed_at',    // completion timestamp
    ];

    protected $casts = [
        'due_date' => 'datetime',
        'completed_at' => 'datetime',
    ];

    /**
     * Get the student user that owns this remedial task.
     */
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    /**
     * Get the Subject model.
     */
    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    /**
     * Get the teacher who assigned this task.
     */
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    /**
     * Get the submissions for this remedial task.
     */
    public function submissions()
    {
        return $this->hasMany(RemedialTaskSubmission::class, 'remedial_task_id');
    }
}
