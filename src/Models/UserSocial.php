<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class UserSocial extends Authenticatable
{
    protected $table = "sociauth";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id_facebook', 'token', 'name', 'email', 'avatar', 'avatar_original', 'gender'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'remember_token',
    ];
}
