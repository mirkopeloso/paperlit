<?php

namespace App\Models;

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
        'publication' => 'required|regex:[0-9]?',
        'timestamp'  => 'required|date_format:Ymd',
        'user'  => 'required',
        'name'  => 'required',
        'start'  => 'required',
        'stop'  => 'required',



    );

    public function validate($data)
    {
        // make a new validator object
        $v = Validator::make($data, $this->rules);
        // return the result
        return $v->passes();
    }


}
