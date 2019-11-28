<?php


namespace App\Http\Controllers\Api;


use App\Enums\UserAnswerEnum;
use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Forms\UserErrorTopic\UserErrorTopicPageForm;
use App\Http\Resources\UserErrorTopic\UserErrorTopicPageResource;
use App\Models\UserAnswerHistoryModel;

class UserErrorTopicController extends Controller
{
    public function list(UserErrorTopicPageForm $form)
    {
        $input = $form->input();
        $userErrorTopicBuilder = UserAnswerHistoryModel::query();
        $userErrorTopicPaginate = $userErrorTopicBuilder
            ->whereUserId(\UserService::getCurrentUserId())
            ->where('content->error_rate', '>', 0)
            ->skip($input['page'] ?? 1)
            ->paginate($input['size'] ?? null);
        return ApiResponse::success(UserErrorTopicPageResource::collection($userErrorTopicPaginate));
    }

    public function detail(int $id)
    {
        $userErrorTopic = UserAnswerHistoryModel::query()->find($id);
        $errorTopics = [];
        $content = $userErrorTopic->content;
        foreach ($content->topic as $item) {
            if ($item->user_answer_status == UserAnswerEnum::ANSWER_STATUS_ERR) {
                $errorTopics[] = $item;
            }
        }

        $content->topic = $errorTopics;
        $userErrorTopic->content = $content;
        return ApiResponse::success();
    }
}