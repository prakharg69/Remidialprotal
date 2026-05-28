<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Assessment extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';
    protected $collection = 'assessments';

    protected $fillable = [
        'student_id',        // references user_id of the student
        'subject_id',        // references subject_id of the Subject model
        'subject',           // fallback name of subject (e.g. Basic Electrical)
        'teacher_id',        // references user_id of the teacher who scored this
        'remedial_resource', // attached study resources / instructions
        'ca1',               // Continuous Assessment 1 (out of 30)
        'ca2',               // Continuous Assessment 2 (out of 30)
        'end_term',          // End Term Exam (out of 40)
        'total',             // Total Marks (computed out of 100)
    ];

    /**
     * Automatically compute total marks when saving the model.
     */
    protected static function booted()
    {
        static::saving(function ($assessment) {
            $assessment->total = floatval($assessment->ca1 ?? 0) + floatval($assessment->ca2 ?? 0) + floatval($assessment->end_term ?? 0);
        });
    }

    /**
     * Get obtained marks so far (sum of non-null components).
     */
    public function getObtainedAttribute()
    {
        return floatval($this->ca1 ?? 0) + floatval($this->ca2 ?? 0) + floatval($this->end_term ?? 0);
    }

    /**
     * Get max possible marks so far (sum of max limits of non-null components).
     */
    public function getMaxPossibleAttribute()
    {
        $max = 0;
        if (!is_null($this->ca1)) $max += 30;
        if (!is_null($this->ca2)) $max += 30;
        if (!is_null($this->end_term)) $max += 40;
        return $max;
    }

    /**
     * Get percentage so far.
     */
    public function getPercentageAttribute()
    {
        $max = $this->max_possible;
        return $max > 0 ? round(($this->obtained / $max) * 100, 1) : 0;
    }

    /**
     * Get the student user that owns this assessment.
     */
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    /**
     * Get the Subject model.
     */
    public function subjectRelation()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    /**
     * Get the teacher who graded this assessment.
     */
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }
}
