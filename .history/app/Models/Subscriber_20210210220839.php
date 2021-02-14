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
        'publication' => 'required|numeric',
        'timestamp'  => 'required|date_format:Ymd',
        'user'  => 'required|numeric',
        'name'  => 'required|alpha',
        'start'  => 'required|date_format:Ymd',
        'stop'  => 'required|date_format:Ymd',



    );

    public function validate($data)
    {
        $v = Validator::make($data, $this->rules);
        return $v->passes();
    }


}
