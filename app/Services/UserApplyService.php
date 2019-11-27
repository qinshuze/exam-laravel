<?php


namespace App\Services;


use App\Enums\UserApplyEnum;
use App\Models\UserApplyModel;
use Illuminate\Auth\AuthenticationException;

class UserApplyService
{
    public function applyExaminer(int $user_id, array $data)
    {
        $userApplyModel = $this->getByUserId($user_id);
        if ($userApplyModel) throw new AuthenticationException('你已经申请过，不能再次申请');
        $userApplyModel = new UserApplyModel($data);
        $userApplyModel->user_id = $user_id;
        $userApplyModel->status = UserApplyEnum::STATUS_WAIT;
        $userApplyModel->save();

        return $userApplyModel;
    }

    public function getByUserId(int $user_id)
    {
        $userApplyModel = UserApplyModel::query()->whereUserId($user_id)->first();
        return $userApplyModel;
    }
}