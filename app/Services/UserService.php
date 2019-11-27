<?php


namespace App\Services;

use App\Models\UserModel;
use App\Models\WeChatAuthModel;

class UserService
{

    /**
     * 获取当前用户的id
     * @return int
     */
    public function getCurrentUserId(): int
    {
        return $this->getCurrentUser()->id;
    }

    public function getCurrentUser()
    {
        return \JWTAuth::parseToken()->authenticate();
    }

    public function registerByWechat(array $data)
    {
        \DB::beginTransaction();
        try {
            $userModel = new UserModel($data);
            $userModel->save();
            $userModel->assignRole('answerer');
            $wechatAuthModel = new WeChatAuthModel($data);
            $wechatAuthModel->user_id = $userModel->id;
            $wechatAuthModel->save();
            \DB::commit();
            return $userModel;
        } catch (\Exception $exception) {
            \DB::rollBack();
            throw $exception;
        }
    }

    public function getById(int $id)
    {
        return UserModel::query()->find($id);
    }
}