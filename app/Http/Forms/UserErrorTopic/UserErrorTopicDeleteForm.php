<?php


namespace App\Http\Forms\UserErrorTopic;


use Illuminate\Foundation\Http\FormRequest;

class UserErrorTopicDeleteForm extends FormRequest
{
    public function rules()
    {
        return [
            'topic_ids' => ['required', 'array']
        ];
    }
}