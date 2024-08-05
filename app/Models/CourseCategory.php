<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class CourseCategory extends Model
{
    use HasFactory;

    public function course():HasMany
    {
        return $this->hasMany(Course::class);
    }

    public function student():HasOne
    {
        return $this->hasOne(Student::class);
    }

    public function teacher():HasOne
    {
        return $this->hasOne(Teacher::class,'id');
    }
}
