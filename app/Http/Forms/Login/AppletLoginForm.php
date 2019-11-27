<?php


namespace App\Http\Forms\Login;


use Illuminate\Foundation\Http\FormRequest;

class AppletLoginForm extends FormRequest
{
    public function rules()
    {
        return [
            'code'           => ['required'],
            'encrypted_data' => ['required'],
            'iv'             => ['required'],
        ];
    }
}