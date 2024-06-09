<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Assignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'module_id',
        'batch_id',
        'time',
        'date',
        'assignment_name',
        'question_link',
    ];

    public function batch():BelongsTo
    {
        return $this->belongsTo(Batch::class);
    }
}
