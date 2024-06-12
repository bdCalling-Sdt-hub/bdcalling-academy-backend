<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FreeSemenar extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'eamil',
        'phone',
        'address',
        'category',
    ];
}
