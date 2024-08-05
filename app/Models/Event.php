<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_name',
        'date',
        'time',
        'end_time',
        'status',
        'image',
        'locations',
        'descriptions'
    ];
}
