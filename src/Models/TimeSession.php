<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TimeSession extends Model
{
    protected $table = "token_user_social";
    protected $fillable = [
        'token_init', 'token_end', 'user_id',
    ];
}
