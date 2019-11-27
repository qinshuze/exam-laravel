<?php


namespace App\Http\Forms\UserApply;


use Illuminate\Foundation\Http\FormRequest;

class UserApplyExaminerForm extends FormRequest
{
    public function rules()
    {
        return [
            'username' => ['required'],
            'phone' => ['required', 'phone'],
            'wechat' => []
        ];
    }
}