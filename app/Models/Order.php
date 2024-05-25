<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'add_student_id',
        'batch_id',
        'course_id',
        'gateway_name',
        'amount',
        'transaction_id',
        'currency',
       
        
    ];
}
