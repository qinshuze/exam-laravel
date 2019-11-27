<?php


namespace App\Http\Forms\UserAnswer;


use Illuminate\Foundation\Http\FormRequest;

class UserAnswerPaperSaveForm extends FormRequest
{
    public function rules()
    {
        return [
            'archives'         => ['array'],
            'archives.*.id'    => ['integer'],
            'archives.*.value' => ['array'],
            'topic'            => ['array'],
            'topic.*.id'       => ['integer'],
            'topic.*.answer'   => ['array'],
        ];
    }
}