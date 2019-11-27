<?php


namespace App\Http\Forms;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PaperCreateForm extends FormRequest
{
    public function rules()
    {
        return [
            'title'          => ['max:255', Rule::unique('paper', 'title')->whereNull('deleted_at')],
            'paper_type_id'  => ['required', 'integer', Rule::exists('paper_type', 'id')],
            'front_cover_id' => ['integer', Rule::exists('upload_file', 'id')],
            'status'         => ['entry:paper_status'],
        ];
    }

    public function messages()
    {
        return [
            'title.max'              => '标题不能大于255个字符',
            'title.unique'           => '标题已被使用',
            'paper_type_id.required' => '类型不能为空',
            'paper_type_id.integer'  => '类型格式不正确',
            'paper_type_id.exists'   => '类型不存在',
            'front_cover_id.integer' => '参数:front_cover_id必须是一个整数',
            'front_cover_id.exists'  => '参数:front_cover_id不存在',
            'status.entry'           => '无效的状态',
        ];
    }
}