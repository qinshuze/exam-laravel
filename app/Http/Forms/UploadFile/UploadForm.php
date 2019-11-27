<?php


namespace App\Http\Forms\UploadFile;


use Illuminate\Foundation\Http\FormRequest;

class UploadForm extends FormRequest
{
    public function rules()
    {
        return [
            'file' => ['required', 'file']
        ];
    }

    public function messages()
    {
        return [
            'file.required' => '参数:file不能为空',
            'file.file' => '参数:file必须是一个文件',
        ];
    }
}