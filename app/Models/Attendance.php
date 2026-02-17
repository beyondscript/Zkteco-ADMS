<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $connection = 'mysql2';
    
    protected $table = 'attendances';

    protected $fillable = [
        'attendance_date',
        'attendance_time',
        'status',
        'user_id',
    ];
}
