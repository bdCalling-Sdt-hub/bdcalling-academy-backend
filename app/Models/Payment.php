<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    protected $fillable = [
        'teacher_id',
        'course_module_id',
        'payment_type',
        'amount',
        'payment_date',
        'reference_by'
    ];
}
