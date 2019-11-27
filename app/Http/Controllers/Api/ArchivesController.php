<?php


namespace App\Http\Controllers\Api;


use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;

class ArchivesController extends Controller
{
    public function list()
    {
        return ApiResponse::success([
            [
                'id' => 1,
                'title' => '姓名',
                'type' => 'input',
                'value' => [],
                'option' => [],
                'is_edit' => false,
            ],
            [
                'id' => 2,
                'title' => '性别',
                'type' => 'radio',
                'value' => [],
                'option' => [
                    '男',
                    '女'
                ],
                'is_edit' => false,
            ],
            [
                'id' => 3,
                'title' => '部门',
                'type' => 'select',
                'value' => [],
                'option' => [
                    '技术部',
                    '客服部'
                ],
                'is_edit' => true,
            ]
        ]);
    }
}