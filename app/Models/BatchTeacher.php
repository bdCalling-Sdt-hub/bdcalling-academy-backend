<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BatchTeacher extends Model
{
    use HasFactory;

    public function teachers():HasMany
    {
        return $this->hasMany(Teacher::class);
    }

    public function batch():BelongsTo
    {
        return $this->belongsTo(Batch::class);
    }
}
