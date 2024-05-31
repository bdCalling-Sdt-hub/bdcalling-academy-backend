<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quize extends Model
{
    use HasFactory;

    protected $fillable = [
        
        'course_module_id',
        'questions',
        'currect_ans',
        'opt_1',
        'opt_2',
        'opt_3',
        'opt_4',
        'mark',
       
        
    ];
}
