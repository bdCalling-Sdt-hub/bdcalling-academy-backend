<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'course_id',
        'batch_id',
        'rating_value',
        'message',
    ];

    public function student():BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}
