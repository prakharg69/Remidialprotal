<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Student extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';
    protected $collection = 'students';

    protected $fillable = [
        'user_id',
        'class_code', // e.g. BCA-3A
        'class',      // e.g. BCA 3rd Year (Section A)
        'roll_number',
        'phone',
        'address',
    ];

    /**
     * Get the User that owns this Student profile.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get assessments for this student.
     */
    public function assessments()
    {
        return $this->hasMany(Assessment::class, 'student_id', 'user_id');
    }

    /**
     * Get attendance records for this student.
     */
    public function attendance()
    {
        return $this->hasMany(Attendance::class, 'student_id', 'user_id');
    }

    /**
     * Get remedial tasks for this student.
     */
    public function remedialTasks()
    {
        return $this->hasMany(RemedialTask::class, 'student_id', 'user_id');
    }

    /**
     * Get teacher feedback for this student.
     */
    public function feedback()
    {
        return $this->hasMany(Feedback::class, 'student_id', 'user_id');
    }
}
