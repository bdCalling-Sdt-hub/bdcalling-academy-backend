<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'phone_number'
    ];

    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function category():BelongsTo
    {
        return $this->belongsTo(CourseCategory::class);
    }

    public function batches():BelongsToMany
    {
        return $this->belongsToMany(Batch::class,'batch_students','student_id','batch_id');
    }

    public function order():HasOne
    {
        return $this->hasOne(Order::class);
    }
}
