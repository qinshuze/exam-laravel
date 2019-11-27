<?php


namespace App\Http\Forms\PaperTopic;


use Illuminate\Foundation\Http\FormRequest;

class PaperTopicImportForm extends FormRequest
{
    public function rules()
    {
        return [
            'excel' => ['file']
        ];
    }
}