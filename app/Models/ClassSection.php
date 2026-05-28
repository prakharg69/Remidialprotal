<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ClassSection extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';
    protected $collection = 'classes';

    protected $fillable = [
        'code', // e.g. BCA-3A
        'name', // e.g. BCA 3rd Year (Section A)
    ];
}
