<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Assignment extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';
    protected $collection = 'assignments';

    protected $fillable = [
        'teacher_id',
        'title',
        'description',
        'subject',
        'class_code',
        'due_date',
        'max_score',
    ];

    protected $casts = [
        'due_date' => 'datetime',
    ];

    /**
     * Get the teacher who created this assignment.
     */
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    /**
     * Get all student submissions for this assignment.
     */
    public function submissions()
    {
        return $this->hasMany(AssignmentSubmission::class, 'assignment_id');
    }
}
