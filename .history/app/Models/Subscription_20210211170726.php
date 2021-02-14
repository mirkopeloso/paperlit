<?php

namespace App\Models;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{

    protected $fillable = [
        'type',
        'start',
        'stop',
    ];
    protected $casts = [
        'start' => 'datetime',
        'stop' => 'datetime',
    ];

}
