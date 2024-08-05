<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

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

    public function videos():HasMany
    {
        return $this->hasMany(Video::class)->orderBy('order');
    }

    public function routine():HasOne
    {
        return $this->hasOne(Routine::class);
    }

    public function quiz():HasOne
    {
        return $this->hasOne(Quize::class);
    }
}
