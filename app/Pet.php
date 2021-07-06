<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pet extends Model
{
    //
    protected $fillable = [
        "name", "gender" , "image", "breed", "address", "age", "weight", "medicalCondition", "temperament", "neutered",
        "created","updated","paid","identity_code"
    ];

    function owners() {
        return $this->hasMany(Owner::class);
    }

    function user() {
        return $this->belongsTo(User::class,"user_id");
    }
}
