<?php


namespace App\Http\Controllers\Api;


use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\User\UserInfoResource;
use App\Services\UserService;

class UserController extends Controller
{
    /**
     * @var UserService
     */
    private $userService;

    public function getUserInfo()
    {
        return ApiResponse::success(new UserInfoResource($this->userService->getCurrentUser()));
    }
}