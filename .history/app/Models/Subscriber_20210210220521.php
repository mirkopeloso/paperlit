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
        'publication' => 'required|alpha|min:3',
        'timestamp'  => 'required',
        'timestamp'  => 'required',
        'timestamp'  => 'required',
        'timestamp'  => 'required',
        'timestamp'  => 'required',



    );

    public function validate($data)
    {
        // make a new validator object
        $v = Validator::make($data, $this->rules);
        // return the result
        return $v->passes();
    }


}
