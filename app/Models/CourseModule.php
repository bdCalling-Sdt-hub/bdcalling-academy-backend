<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CourseModule extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'module_title',
        'created_by',
        "module_no",
        'module_class'
    ];

    public function course():BelongsTo
    {
        return $this->belongsTo(Course::class);
    }
}
