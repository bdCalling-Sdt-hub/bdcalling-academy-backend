<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;
//use App\Notification\SendSmsNotifications;
class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;


    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'otp',
        'verify_email_at'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function teacher():HasOne
    {
        return $this->hasOne(Teacher::class);
    }

    public function leave_application():HasMany
    {
        return $this->hasMany(LeaveApplication::class);
    }





    public function batches():BelongsToMany
    {
        return $this->belongsToMany(Batch::class, 'batch_teachers', 'user_id', 'batch_id');
    }

    public function student():HasOne
    {
        return $this->hasOne(Student::class);
    }

    public function feedback():HasOne
    {
        return $this->hasOne(StudentFeedback::class);
    }

    public function trainer_review():HasOne
    {
        return $this->hasOne(TrainerReview::class);
    }


}
