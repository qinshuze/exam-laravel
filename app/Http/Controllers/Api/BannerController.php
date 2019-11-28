<?php


namespace App\Http\Controllers\Api;


use App\Enums\BannerTypeEnum;
use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Forms\Banner\BannerPageForm;
use App\Http\Resources\Banner\BannerPageResource;
use App\Models\BannerModel;

class BannerController extends Controller
{
    public function list(BannerPageForm $form)
    {
        $queryBuilder = BannerModel::query();
        $bannerPaginate = $queryBuilder->whereType(BannerTypeEnum::APPLET_TOP)->skip($form->input('page', 1))->paginate($form->input('size'));
        return ApiResponse::success(BannerPageResource::collection($bannerPaginate));
    }
}