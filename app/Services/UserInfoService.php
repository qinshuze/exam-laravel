<?php


namespace App\Services;


use App\Models\UserInfoModel;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserInfoService
{
    /**
     * @param int $user_id
     * @return UserInfoModel|UserInfoModel[]|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     */
    public function getByUserId(int $user_id)
    {
        return UserInfoModel::query()->find($user_id);
    }

    /**
     * @param int $user_id
     * @param array $data
     * @return UserInfoModel
     */
    public function create(int $user_id, array $data)
    {
        $userInfoModel = new UserInfoModel($data);
        $userInfoModel->user_id = $user_id;
        $userInfoModel->save();

        return $userInfoModel;
    }

    /**
     * @param int $user_id
     * @param array $data
     * @return UserInfoModel|UserInfoModel[]|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     */
    public function update(int $user_id, array $data)
    {
        $userInfoModel = $this->getByUserId($user_id);
        if (!$userInfoModel) throw new NotFoundHttpException('系统找不到指定用户信息');
        $userInfoModel->setRawAttributes($data);
        $userInfoModel->user_id = $user_id;
        $userInfoModel->update();

        return $userInfoModel;
    }
}