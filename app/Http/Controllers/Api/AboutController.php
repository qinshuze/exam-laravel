<?php


namespace App\Http\Controllers\Api;


use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;

class AboutController extends Controller
{
    public function getExcelTemplate()
    {
        $doc = config('system.materials.excel_template', '');
        return\Storage::disk('template')->download($doc);
    }

    public function getHelperDoc()
    {
        $doc = config('system.materials.helper', []);
        return ApiResponse::success($doc);
    }

    public function getContactDetails()
    {
        return ApiResponse::success(config('system.contact_information', []));
    }
}