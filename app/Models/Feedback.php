<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Feedback extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';
    protected $collection = 'feedback';

    protected $fillable = [
        'student_id', // references user_id of the student
        'teacher_id', // references user_id of the teacher
        'remark',
    ];

    /**
     * Get the student user who received this feedback.
     */
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    /**
     * Get the teacher user who gave this feedback.
     */
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }
}
