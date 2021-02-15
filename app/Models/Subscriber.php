<?php

namespace App\Models;

use Illuminate\Support\Facades\Validator;

/**
 * @author Mirko Peloso <mirko.peloso@gmail.com>
 *
 * Class for xml subscriber parsing
 */
class Subscriber
{

    /**
     * Validators
     *
     * @var array
     */
    private $rules = array(
        'publication' => 'required|numeric',
        'timestamp'   => 'required|date_format:Ymd',
        'user'        => 'required|numeric',
        'name'        => 'required|string',
        'start'       => 'required|date_format:Ymd',
        'stop'        => 'required|date_format:Ymd|after:start',

    );

    protected $errors;

    /**
     * validates an associative array of single subscriber datas
     *
     * @param [type] $data
     * @return void
     */
    public function validate($data)
    {
        $v = Validator::make($data, $this->rules);
        if ($v->fails()) {
            $this->errors = $v->errors();
            return false;
        }

        return true;
    }

    /**
     * Returns validation errors if exists
     *
     * @return void
     */
    public function errors()
    {
        return $this->errors;
    }

}
