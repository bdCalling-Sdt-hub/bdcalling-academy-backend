<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class AddStudent extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'batch_id',
        'user_id',
        'course_id',
        'phone',
        'gender',
        'riligion',
        'registration_date',
        'dob',
        'blood_group',
        'address',
        'add_by',
        'student_type',
    ];



        public function user()
        {
            return $this->belongsTo(User::class);
        }

        public function batch()
        {
            return $this->belongsTo(Batch::class);
        }

        public function course()
        {
            return $this->belongsTo(Course::class);
        }

        public function orders()
        {
            return $this->hasMany(Order::class);
        }

        public function batches():BelongsToMany
        {
            return $this->belongsToMany(Batch::class,'batch_students','student_id','batch_id');
        }



}
