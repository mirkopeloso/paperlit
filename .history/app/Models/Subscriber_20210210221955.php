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

    protected $errors;


    public function validate($data)
    {
        $v = Validator::make($data, $this->rules, $this->messages);
        if ($v->fails())
        {

            var_dump($v);
            die();

            $this->errors = $v->errors;
            return false;
        }

        return true;
    }

    public function errors()
    {
        return $this->errors;
    }

}
