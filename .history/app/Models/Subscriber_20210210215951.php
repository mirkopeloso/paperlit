<?php

namespace App\Models;

class Subscription
{

    protected $fillable = [
        'publication',
        'timestamp',
        'user',
        'name',
        'start',
        'stop',
    ];

}
