<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Config;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'username', 'email', 'password', 'role'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function isAdmin() {
        if($this->role == Config::get('constants.ADMIN_ROLE')){
            return true;
        } else {
            return false;
        }
    }

    public function isRenter() {
        if($this->role == Config::get('constants.RENTER_ROLE')){
            return true;
        } else {
            return false;
        }
    }

    /**
    * Add a mutator to ensure hashed passwords
    */
    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = bcrypt($password);
    }

    // public function role() {
    //     return $this->hasOne('App\User', 'hierarchy', 'role');
    // }

    public function books()
    {
        return $this->hasMany('App\Books', 'book_id', 'id');
    }

    public function rentHistories()
    {
        return $this->hasMany('App\RentHistory', 'user_id', 'id');
    }
}
