<?php


namespace App\Http\Controllers\Api;


use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Forms\PaperApply\PaperApplyPageForm;
use App\Models\PaperApplyModel;
use App\Models\PaperModel;
use App\Services\PaperApplyService;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PaperApplyController extends Controller
{
    /**
     * @var PaperApplyService
     */
    private $paperApplyService;

    /**
     * PaperApplyController constructor.
     * @param PaperApplyService $paperApplyService
     */
    public function __construct(PaperApplyService $paperApplyService)
    {
        $this->paperApplyService = $paperApplyService;
    }

    public function list(int $paper_id, PaperApplyPageForm $form)
    {
        $condition = $form->input('condition');
        $page = $form->input('page');
        $size = $form->input('size');

        $paperModel = PaperModel::query()->find($paper_id);
        if (!$paperModel) throw new NotFoundHttpException('考卷不存在或已被删除');
        $paperApplyPaginate = PaperApplyModel::query()
            ->join('users', 'users.id', '=', 'paper_apply.user_id')
            ->wherePaperId($paper_id)
            ->skip($page)
            ->selectRaw('paper_apply.id,paper_apply.status,paper_apply.user_id,users.nickname,users.avatar')
            ->paginate($size);

        return ApiResponse::success($paperApplyPaginate);
    }

    public function apply(int $paper_id)
    {
        $paperApplyModel = $this->paperApplyService->apply(\UserService::getCurrentUserId(), $paper_id);
        return ApiResponse::success($paperApplyModel);
    }

    public function pass(int $id)
    {
        $paperApplyModel = $this->paperApplyService->pass($id);
        return ApiResponse::success($paperApplyModel);
    }

    public function notPass(int $id)
    {
        $paperApplyModel = $this->paperApplyService->notPass($id);
        return ApiResponse::success($paperApplyModel);
    }

    public function kickOut(int $id)
    {
        $this->paperApplyService->delete($id);
        return ApiResponse::success();
    }
}