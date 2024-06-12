<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'batch_id',
        'gateway_name',
        'amount',
        'transaction_id',
        'payment_type',
    ];

    public function student():BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function batch():BelongsTo
    {
        return $this->belongsTo(Batch::class);
    }

    public function getInstallmentDateAttribute($value)
    {
        return json_decode($value);
    }
}
