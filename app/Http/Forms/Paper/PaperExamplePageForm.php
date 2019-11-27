<?php


namespace App\Http\Forms\Paper;


use Illuminate\Foundation\Http\FormRequest;

class PaperExamplePageForm extends FormRequest
{
    public function rules()
    {
        return [
            'condition' => ['array'],
            'page'      => ['integer'],
            'size'      => ['integer'],
        ];
    }

    public function messages()
    {
        return [
            'condition.array' => '参数:condition必须是一个数组',
            'page.integer'    => '参数:page必须是一个整数',
            'size.integer'    => '参数:size必须是一个整数',
        ];
    }
}