<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PhoneNumber extends Model
{
    //
    protected $fillable = ['owner_id','phone_number'];
    
    function Owner() {
        return $this->belongsTo(Owner::class,"owner_id");
    }
}
