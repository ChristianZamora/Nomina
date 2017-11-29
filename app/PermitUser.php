<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PermitUser extends Model
{

    protected $fillable = [
        "user_id",
        "permit_id"
    ];
    
}
