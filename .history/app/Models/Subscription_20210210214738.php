<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Subscription
{

    protected $fillable = [
        'publication' => string '154' (length=3)
        'timestamp' => string '20200810' (length=8)
        'user' => string '410343867' (length=9)
        'name' => string 'BONINI' (length=6)
        'start' => string '20200718' (length=8)
        'stop' => string '20210717' (length=8)
    ];

}
