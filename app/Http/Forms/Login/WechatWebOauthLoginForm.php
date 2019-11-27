<?php


namespace App\Http\Forms\Login;


use Illuminate\Foundation\Http\FormRequest;

class WechatWebOauthLoginForm extends FormRequest
{
    public function rules()
    {
        return [
            'code'           => ['required'],
        ];
    }
}