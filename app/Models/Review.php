<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'add_students_id',
        'course_id',
        'batch_id',
        'rating_value',
        'message',        
    ];
}
