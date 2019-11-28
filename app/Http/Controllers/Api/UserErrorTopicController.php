<?php


namespace App\Http\Controllers\Api;


use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Forms\UserErrorTopic\UserErrorTopicDeleteForm;
use App\Http\Forms\UserErrorTopic\UserErrorTopicPageForm;
use App\Http\Resources\UserErrorTopic\UserErrorTopicPageResource;
use App\Models\UserAnswerHistoryModel;
use App\Models\UserErrorTopicModel;

class UserErrorTopicController extends Controller
{
    public function list(UserErrorTopicPageForm $form)
    {
        $input                  = $form->input();
        $userErrorTopicBuilder  = UserErrorTopicModel::query();
        $userErrorTopicPaginate = $userErrorTopicBuilder
            ->whereUserId(\UserService::getCurrentUserId())
            ->skip($input['page'] ?? 1)
            ->paginate($input['size'] ?? null);
        return ApiResponse::success(UserErrorTopicPageResource::collection($userErrorTopicPaginate));
    }

    public function detail(int $id)
    {
        $userErrorTopic = UserErrorTopicModel::query()->find($id);
        return ApiResponse::success($userErrorTopic);
    }

    public function delete(int $id, UserErrorTopicDeleteForm $form)
    {
        $topicIds = $form->input('topic_ids');
        $userErrorTopic = UserAnswerHistoryModel::query()->find($id);
        $content        = $userErrorTopic->content;
        $newErrorTopicList = [];
        foreach ($content->topic as $key => $item) {
            if (in_array($item->id, $topicIds)) continue;
            $newErrorTopicList[] = $item;
        }

        $content->topic = $newErrorTopicList;
        $userErrorTopic->content = $content;
        $userErrorTopic->update();
        return ApiResponse::success();
    }
}