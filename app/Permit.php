<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Permit extends Model
{

    protected $fillable = [
        "nombre"
    ];

    public function user(){

        return $this->belongsTo(User::class);

    }
    //
}
