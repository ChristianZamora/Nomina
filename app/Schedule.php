<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = [
        "entry",
        "break",
        "exit"
    ];

    protected $table = 'schedules';

    public function user(){

        $this->belongsTo(User::class);

    }

}
