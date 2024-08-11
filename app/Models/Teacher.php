<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Teacher extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'course_category_id',
        'phone_number',
        'designation',
        'expert',
    ];

    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function leave_application():HasMany
    {
        return $this->hasMany(LeaveApplication::class);
    }

    public function batches():BelongsToMany
    {
        return $this->belongsToMany(Batch::class, 'batch_teachers', 'teacher_id', 'batch_id');
    }

    public function category():BelongsTo
    {
        return $this->belongsTo(CourseCategory::class, 'course_category_id');
    }

    public function batchTeachers():BelongsTo
    {
        return $this->belongsTo(BatchTeacher::class, 'teacher_id');
    }

    public function payments():HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function trainer_reviews():HasMany
    {
        return $this->hasMany(TrainerReview::class);
    }
}
