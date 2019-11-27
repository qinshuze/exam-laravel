<?php


namespace App\Http\Controllers\Api;


use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;

class DicController extends Controller
{
    public function all()
    {
        $dic = \Dic::all();
        $data = [];
        foreach ($dic as $item) {
            foreach ($item['entry'] as $entry) {
                $data[$item['en_name']][] = [
                    'key' => $entry['index'],
                    'value' => $entry['value'],
                ];
            }
        }

        return ApiResponse::success($data);
    }

    public function refresh()
    {
        $dic = \Dic::refresh();
        return ApiResponse::success();
    }
}