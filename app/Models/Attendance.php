<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Attendance extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';
    protected $collection = 'attendance';

    protected $fillable = [
        'student_id', // references user_id of the student
        'date',       // YYYY-MM-DD
        'status',     // 'present', 'absent'
    ];

    /**
     * Get the student user that owns this attendance record.
     */
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
}
