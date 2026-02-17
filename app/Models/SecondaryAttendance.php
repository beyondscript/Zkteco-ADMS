<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SecondaryAttendance extends Model
{
    protected $table = 'attendances';

    protected $fillable = [
        'device_sn',
        'user_id',
        'ip_address',
        'attendance_time',
    ];
}
