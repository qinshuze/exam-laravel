<?php


namespace App\Http\Controllers\Api;


use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Forms\PaperType\PaperTypeListForm;
use App\Http\Resources\PaperType\PaperTypeListResource;
use App\Services\PaperTypeService;

class PaperTypeController extends Controller
{
    /**
     * @var PaperTypeService
     */
    private $paperTypeService;

    /**
     * PaperTypeController constructor.
     * @param PaperTypeService $paperTypeService
     */
    public function __construct(PaperTypeService $paperTypeService)
    {
        $this->paperTypeService = $paperTypeService;
    }


    public function list(PaperTypeListForm $form)
    {
        $data = $this->paperTypeService->getPageList(
            $form->input('condition'),
            $form->input('page'),
            $form->input('size')
        );

        return ApiResponse::success(PaperTypeListResource::collection($data));
    }
}