<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeaveApplication extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'leave_type',
        'date_from',
        'date_to',
        'phone_number',
        'reason',
    ];

    public function teacher():BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }
    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
