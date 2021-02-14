<?php

namespace App\Models;
use Illuminate\Support\Facades\Validator;


class Subscription extends Model
{

    protected $fillable = [
        'type',
        'start',
        'stop',
    ];

}
