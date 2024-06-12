<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Course extends Model
{
    use HasFactory;


    public function batch():HasMany
    {
        return $this->hasMany(Batch::class);
    }

    public function course_category():BelongsTo
    {
        return $this->belongsTo(CourseCategory::class);
    }

    public function course_module():HasMany
    {
        return $this->hasMany(CourseModule::class);
    }

    public function addStudents()
    {
        return $this->hasMany(AddStudent::class);
    }

    public function getCareerOpportunitiesAttribute($value)
    {
        return json_decode($value);
    }

    public function getCurriculumAttribute($value)
    {
        return json_decode($value);
    }
    public function getToolsAttribute($value)
    {
        return json_decode($value);
    }

    public function getJobPositionAttribute($value)
    {
        return json_decode($value);
    }
}
