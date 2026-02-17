<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    protected $fillable = [
        'serial_number',
        'ip_address',
        'last_online',
    ];
}
