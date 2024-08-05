<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncludeCost extends Model
{
    use HasFactory;

    protected $fillable = [
        'costing',
    ];

    public function getCostingAttribute($value)
    {
        return json_decode($value);
    }
}

