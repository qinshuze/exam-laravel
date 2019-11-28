<?php


namespace App\Http\Controllers\Api;


use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Forms\UserAnswerHistory\UserAnswerHistoryPageForm;
use App\Http\Resources\UserAnswerHistory\UserAnswerHistoryDetailResource;
use App\Http\Resources\UserAnswerHistory\UserAnswerHistoryPageResource;
use App\Models\UserAnswerHistoryModel;

class UserAnswerHistoryController extends Controller
{
    public function list(UserAnswerHistoryPageForm $form)
    {
        $input = $form->input();
        $userAnswerHistoryBuilder = UserAnswerHistoryModel::query();
        $userAnswerHistoryPaginate = $userAnswerHistoryBuilder->whereUserId(\UserService::getCurrentUserId())->skip($input['page'] ?? 1)->paginate($input['size'] ?? null);
        return ApiResponse::success(UserAnswerHistoryPageResource::collection($userAnswerHistoryPaginate));
    }

    public function detail(int $id)
    {
        $userAnswerHistoryModel = UserAnswerHistoryModel::query()->find($id);
        return ApiResponse::success(new UserAnswerHistoryDetailResource($userAnswerHistoryModel));
    }
}