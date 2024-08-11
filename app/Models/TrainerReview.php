<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TrainerReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'teacher_id',
        'batch_id',
        'rating_value',
        'review'
    ];

    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function teacher():BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }
}
