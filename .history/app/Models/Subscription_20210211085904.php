<?php

namespace App\Models;
use Illuminate\Support\Facades\Validator;


class Subscription
{

    protected $fillable = [
        'type',
        'start',
        'stop',
    ];

}
