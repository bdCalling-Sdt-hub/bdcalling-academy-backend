<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Quize extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_module_id',
        'questions',
        'exam_name',
        'mark',
    ];

    public function getQuestionsAttribute($value)
    {
        return json_decode($value);
    }

    public function course_module(): BelongsTo
    {
        return $this->belongsTo(CourseModule::class);
    }
}
