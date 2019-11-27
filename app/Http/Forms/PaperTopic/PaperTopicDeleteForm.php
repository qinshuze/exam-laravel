<?php


namespace App\Http\Forms\PaperTopic;


use Illuminate\Foundation\Http\FormRequest;

class PaperTopicDeleteForm extends FormRequest
{
    public function rules()
    {
        return [
            'ids' => ['array'],
            'ids.*' => ['integer'],
        ];
    }

    public function messages()
    {
        return [
            'ids.array' => '参数:ids 必须是一个有效的数组格式',
            'ids.*.integer' => 'ids[*]必须是一个有效的整数格式',
        ];
    }
}