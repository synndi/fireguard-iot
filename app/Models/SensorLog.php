<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SensorLog extends Model
{
    protected $fillable = [
        'mq2_value',
        'flame_value',
        'status',
    ];
}
