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
}
