<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;

class FileUploadRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        return [
            'code'   => 'required|regex:/[A-z0-9]*/',  // Tutte le lettere accentate e non, numeri, spazio, punto, apice, trattino e chiocciola.
            'email'      => 'required|email|max:64'
        ];
    }

}
