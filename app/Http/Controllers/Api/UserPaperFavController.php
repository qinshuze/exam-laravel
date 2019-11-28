<?php


namespace App\Http\Controllers\Api;


use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Forms\UserPaperFav\UserPaperFavAddForm;
use App\Http\Forms\UserPaperFav\UserPaperFavPageForm;
use App\Http\Resources\UserPaperFav\UserPaperFavPageResource;
use App\Models\UserPaperFavModel;

class UserPaperFavController extends Controller
{
    public function list(UserPaperFavPageForm $form)
    {
        $input = $form->input();
        $userPaperFavBuilder = UserPaperFavModel::query();
        $userPaperFavPaginate = $userPaperFavBuilder->whereUserId(\UserService::getCurrentUserId())->skip($input['page'] ?? 1)->paginate($input['size'] ?? null);
        return ApiResponse::success(UserPaperFavPageResource::collection($userPaperFavPaginate));
    }

    public function add(UserPaperFavAddForm $form)
    {
        $paperId = $form->input('paper_id');
        $userPaperFavModel = UserPaperFavModel::query()->whereUserId(\UserService::getCurrentUserId())->wherePaperId($paperId)->first();
        if (!$userPaperFavModel) {
            $userPaperFavModel = new UserPaperFavModel();
            $userPaperFavModel->user_id = \UserService::getCurrentUserId();
            $userPaperFavModel->paper_id = $paperId;
            $userPaperFavModel->save();
        }

        return ApiResponse::success();
    }

    public function delete(int $paper_id)
    {
        UserPaperFavModel::query()->whereUserId(\UserService::getCurrentUserId())->wherePaperId($paper_id)->delete();
        return ApiResponse::success();
    }
}