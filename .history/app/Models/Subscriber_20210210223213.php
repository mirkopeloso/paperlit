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
        'name'  => 'required|string',
        'start'  => 'required|date_format:Ymd',
        'stop'  => 'required|date_format:Ymd',



    );

    protected $errors;

    public function validate($data)
    {
        $v = Validator::make($data, $this->rules);
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
