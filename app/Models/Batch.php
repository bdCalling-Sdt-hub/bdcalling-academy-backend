<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Routing\Route;

class Batch extends Model
{
    use HasFactory;

    public function course():BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function course_category():BelongsTo
    {
        return $this->belongsTo(CourseCategory::class);
    }

    public function addStudents()
    {
        return $this->hasMany(AddStudent::class);
    }

    public function user():HasMany
    {
        return $this->hasMany(User::class);
    }

    public function routine():HasMany
    {
        return $this->hasMany(Routine::class);
    }
}
