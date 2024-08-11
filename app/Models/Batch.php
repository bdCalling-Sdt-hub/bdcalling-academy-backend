<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Routing\Route;

class Batch extends Model
{
    use HasFactory;

    protected $fillable = [
       ' course_id',
        'start_date',
        'end_date',
        'seat_limit',
        'seat_left',
        'image',
    ];

    public function course():BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function course_category():BelongsTo
    {
        return $this->belongsTo(CourseCategory::class);
    }

    public function teachers():BelongsToMany
    {
        return $this->belongsToMany(Teacher::class, 'batch_teachers', 'batch_id', 'teacher_id');
    }


    public function routine():HasMany
    {
        return $this->hasMany(Routine::class);
    }

    public function students():BelongsToMany
    {
        return $this->belongsToMany(Student::class,'batch_students','batch_id','student_id');
    }

    public function student():HasOne
    {
        return $this->hasOne(Student::class);
    }

//    public function batch():HasOne
//    {
//        return $this->hasOne(Batch::class);
//    }
    public function assignment():HasOne
    {
        return $this->hasOne(Batch::class);
    }

    public function attendance():HasOne
    {
        return $this->hasOne(Batch::class);
    }

    public function batch_student():hasMany
    {
        return $this->hasMany(BatchStudent::class);
    }

    public function batch_teacher():HasMany
    {
        return $this->hasMany(BatchTeacher::class);
    }
}
