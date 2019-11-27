<?php


namespace App\Http\Forms\UserAnswer;


use Illuminate\Foundation\Http\FormRequest;

class UserAnswerPaperForm extends FormRequest
{
    public function rules()
    {
        return [
            'password' => []
        ];
    }
}