<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class BatchStudent extends Model
{
    use HasFactory;

    public function batch():BelongsTo
    {
        return $this->belongsTo(Batch::class);
    }
    public function student():BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

}
