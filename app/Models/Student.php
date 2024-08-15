<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Notifications\Notifiable;

class Student extends Model
{
    use HasFactory , Notifiable;

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

    public function order():HasMany
    {
        return $this->HasMany(Order::class);
    }

    public function mark():HasOne
    {
        return $this->hasOne(Mark::class);
    }

    public function getMessagesAttribute($value)
    {
        return json_decode($value);
    }

    public function attendance():HasOne
    {
        return $this->hasOne(Attendance::class,'student_id','id');
    }

    public function review():HasOne
    {
        return $this->hasOne(Review::class);
    }

    public function batch_students():HasMany
    {
        return $this->hasMany(BatchStudent::class);
    }


}
