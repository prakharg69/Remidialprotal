<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Subject extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';
    protected $collection = 'subjects';

    protected $fillable = [
        'name',         // Subject Title (e.g. Basic Electrical)
        'code',         // Unique course code (e.g. EEE106)
        'ca1_max',      // max marks for CA1 (default 30)
        'ca2_max',      // max marks for CA2 (default 30)
        'end_term_max', // max marks for End Term (default 40)
    ];
}
