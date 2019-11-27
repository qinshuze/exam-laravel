<?php


namespace App\Http\Forms\PaperTopic;


use Illuminate\Foundation\Http\FormRequest;

class PaperTopicWeightForm extends FormRequest
{
    public function rules()
    {
        return [
            '*.topic_id' => ['required', 'integer'],
            '*.weight' => ['required', 'integer']
        ];
    }
}