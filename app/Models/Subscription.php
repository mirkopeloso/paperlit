<?php

namespace App\Models;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

/**
 * @author Mirko Peloso <mirko.peloso@gmail.com>
 *
 * Class for user subscriptions
 */
class Subscription extends Model
{

    protected $fillable = [
        'type',
        'start',
        'stop',
    ];

    protected $hidden = [
        'id',
        'user_id',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'start' => 'datetime:Ymd',
        'stop' => 'datetime:Ymd',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Ymd');
    }

}
