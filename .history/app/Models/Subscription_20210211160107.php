<?php

namespace App\Models;
use Illuminate\Support\Facades\Validator;
use Model;

class Subscription extends Model
{

    protected $fillable = [
        'id',
        'user_id',
        'type',
        'start',
        'stop',
    ];

}
