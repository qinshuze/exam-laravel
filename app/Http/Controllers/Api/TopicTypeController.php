<?php


namespace App\Http\Controllers\Api;


use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Forms\TopicType\TopicTypeListForm;
use App\Http\Resources\TopicType\TopicTypeListResource;
use App\Services\TopicTypeService;

class TopicTypeController extends Controller
{
    /**
     * @var TopicTypeService
     */
    private $topicTypeService;

    /**
     * TopicTypeController constructor.
     * @param TopicTypeService $topicTypeService
     */
    public function __construct(TopicTypeService $topicTypeService)
    {
        $this->topicTypeService = $topicTypeService;
    }


    public function list(TopicTypeListForm $form)
    {
        $condition = $form->input('condition');
        $page = $form->input('page');
        $size = $form->input('size');
        $data = $this->topicTypeService->getPageList($condition, $page, $size);

        return ApiResponse::success(TopicTypeListResource::collection($data));
    }
}