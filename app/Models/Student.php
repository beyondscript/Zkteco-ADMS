<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $connection = 'mysql2';

    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'student_id',
        'password',
    ];
}
