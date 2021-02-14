<?php

namespace App\Models;
use Illuminate\Support\Facades\Validator;


class Subscriber
{

    protected $fillable = [
        'publication',
        'timestamp',
        'user',
        'name',
        'start',
        'stop',
    ];



    private $rules = array(
        'publication' => 'required|numeric',
        'timestamp'  => 'required|date_format:Ymd',
        'user'  => 'required|numeric',
        'name'  => 'required|alpha',
        'start'  => 'required|date_format:Ymd',
        'stop'  => 'required|date_format:Ymd',



    );

    private $messages = array(
        'publication' => 'Must be numeric',
        'timestamp'  => 'Must be a valid timestamp in YYYYMMDD format',
        'user'  => 'Must be numeric',
        'name'  => 'Must be a valid person lastname',
        'start'  => 'Must be a valid timestamp in YYYYMMDD format',
        'stop'  => 'Must be a valid timestamp in YYYYMMDD format',
    );

    protected $errors;

    public function validate($data)
    {
        $v = Validator::make($data, $this->rules, $this->messages);
        if ($v->fails())
        {
             $this->errors = $v->errors();
            return false;
        }

        return true;
    }

    public function errors()
    {
        return $this->errors;
    }

}
