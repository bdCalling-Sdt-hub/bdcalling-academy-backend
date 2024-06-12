<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Mark extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'batch_id',
        'score',
        'date',
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
