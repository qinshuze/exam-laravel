<?php


namespace App\Http\Forms\PaperTopic;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PaperTopicSaveForm extends FormRequest
{
    public function rules()
    {
        $topic_id = $this->route()->parameter('topic_id') ?? 0;
        $required = $topic_id ? '' : 'required';
        return [
            'topic_type_id'   => [$required, 'integer', Rule::exists('topic_type', 'id')],
            'content'         => [Rule::unique('topic', 'content')->whereNot('id', $topic_id)],
            'media'           => ['array'],
            'option'          => ['array'],
            'option.*.name'   => ['required_with:option'],
            'option.*.value'  => ['required_with:option'],
            'option.*.media'  => ['array'],
            'option.*.answer' => ['required_with:option', 'boolean'],
            'answer'          => ['array'],
            'answer_analysis' => [],
            'score'           => ['integer']
        ];
    }

    public function messages()
    {
        return [
            'topic_type_id.required'        => '题目类型不能为空',
            'topic_type_id.integer'         => '题目类型数据格式不正确',
            'topic_type_id.exists'          => '无效的题目类型',
            'content.unique'                => '已存在同名题目',
            'media.array'                   => '参数:media必须是一个有效的数组格式',
            'option.array'                  => '参数:option必须是一个有效的数组格式',
            'option.*.name.required_with'   => 'option[][name] is not null',
            'option.*.value.required_with'  => 'option[][value] is not null',
            'option.*.media.array'          => 'option[][media] need be array',
            'option.*.answer.required_with' => 'option[][answer] is not null',
            'option.*.answer.boolean'       => 'option[][answer] need be boolean',
            'answer.array'                  => 'answer need be array',
        ];
    }
}