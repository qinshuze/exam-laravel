<?php


namespace App\Services;


use App\Enums\PaperEnum;
use App\Models\PaperApplyModel;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PaperApplyService
{
    /**
     * 根据考卷id删除申请记录
     * @param int $paper_id
     * @return bool|mixed|null
     * @throws \Exception
     */
    public function deleteByPaperId(int $paper_id)
    {
        return PaperApplyModel::query()->wherePaperId($paper_id)->delete();
    }

    public function getById(int $id)
    {
        return PaperApplyModel::query()->find($id);
    }

    public function getApplyUser(int $paper_id, int $user_id)
    {
        return PaperApplyModel::query()->wherePaperId($paper_id)->whereUserId($user_id)->first();
    }

    public function apply(int $paper_id, int $user_id)
    {
        $paperApply = new PaperApplyModel();
        $paperApply->user_id = $user_id;
        $paperApply->paper_id = $paper_id;
        $paperApply->status = PaperEnum::APPLY_STATUS_WAIT;
        $paperApply->save();

        return $paperApply;
    }

    public function pass(int $id)
    {
        return $this->setStatus($id, PaperEnum::APPLY_STATUS_PASS);
    }

    public function notPass(int $id)
    {
        return $this->setStatus($id, PaperEnum::APPLY_STATUS_NOT_PASS);
    }

    public function delete(int $id)
    {
        return PaperApplyModel::query()->whereKey($id)->delete();
    }

    public function setStatus(int $id, int $status)
    {
        $paperApply = PaperApplyModel::query()->find($id);
        if (!$paperApply) throw new NotFoundHttpException('对象不存在或已被删除');
        $paperApply->status = $status;
        $paperApply->update();
        return $paperApply;
    }
}