<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ScheduleUser extends Model
{
    protected $fillable = [
        "schedule_id",
        "user_id"
    ];

    protected $table = 'schedule_users';

    
}
