<?php


namespace App\Http\Forms\PaperTopic;


use Illuminate\Foundation\Http\FormRequest;

class ImportTopicByPaperForm extends FormRequest
{
    public function rules()
    {
        return [
            '*.paper_id' => ['required'],
            '*.topic_id' => ['required']
        ];
    }

    public function messages()
    {
        return [

        ];
    }
}