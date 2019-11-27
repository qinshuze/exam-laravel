<?php


namespace App\Http\Forms\User;


use Illuminate\Foundation\Http\FormRequest;

class UserPhoneEncryptedForm extends FormRequest
{
    public function rules()
    {
        return [
            'encrypted_data' => ['required'],
            'iv'             => ['required'],
        ];
    }
}