<?php

namespace App;

use App\Mail\newUserWelcomeMail;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Mail;
class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','cvc','expiry','number','card_name','membership_plan','membership_created','membership_updated'
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'admin'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public static function isAdmin()
    {
        return ($user = auth()->user()) && $user->admin;
    }

    function Pets() {
        return $this->hasMany(Pet::class);
    }
    
    function Documents() {
        return $this->hasMany(documents::class);
    }
}