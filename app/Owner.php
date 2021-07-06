<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Owner extends Model
{
    //
    protected $fillable = ['pets_id','name'];
    
    function pet() {
        return $this->belongsTo(Pet::class,"pets_id");
    }

    function PhoneNumbers() {
        return $this->hasMany(PhoneNumber::class);
    }

}
