<?php


namespace App\Http\Forms\UserApply;


use Illuminate\Foundation\Http\FormRequest;

class UserApplyEncryptedDataForm extends FormRequest
{
    public function rules()
    {
        return [
            'encrypted_data' => ['required'],
            'iv'             => ['required']
        ];
    }
}