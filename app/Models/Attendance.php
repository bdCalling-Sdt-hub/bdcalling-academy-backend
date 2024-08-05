<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'batch_id',
        'student_id',
        'date',
        'attendance_by',
        'is_present',
    ];

    public function student():BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
    public function batch():BelongsTo
    {
        return $this->belongsTo(Batch::class);
    }

}
