<?php


namespace App\Http\Controllers\Api;


use App\Enums\DicEnum;
use App\Enums\ErrorCodeEnum;
use App\Enums\PaperEnum;
use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Forms\Paper\PaperExamplePageForm;
use App\Http\Forms\Paper\PaperPageForm;
use App\Http\Forms\Paper\PaperUpdateForm;
use App\Http\Forms\PaperCreateForm;
use App\Http\Resources\Paper\PaperCloneResource;
use App\Http\Resources\Paper\PaperCreateResource;
use App\Http\Resources\Paper\PaperDetailResource;
use App\Http\Resources\Paper\PaperExamplePageResource;
use App\Http\Resources\Paper\PaperListResource;
use App\Http\Resources\Paper\PaperUpdateResource;
use App\Models\PaperModel;
use App\Models\UploadFileModel;
use App\Services\PaperService;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PaperController extends Controller
{
    /**
     * @var PaperService
     */
    private $paperService;

    /**
     * PaperController constructor.
     * @param PaperService $paperService
     */
    public function __construct(PaperService $paperService)
    {
        $this->paperService = $paperService;
    }

    /**
     * 创建考卷
     * @param PaperCreateForm $form
     * @return array
     * @throws \Exception
     */
    public function create(PaperCreateForm $form)
    {
        $input = $form->input();
        // 由于表结构有变化这里需要将字段做一些转换处理
        if (isset($input['front_cover_id'])) {
            $uploadFile = UploadFileModel::query()->find($input['front_cover_id']);
            if (!$uploadFile) throw new NotFoundHttpException('封面不存在或已被删除');
            $input['front_cover'] = $uploadFile->path;
        }

        $paper = $this->paperService->create($form->input());
        return ApiResponse::success(new PaperCreateResource($paper));
    }

    /**
     * 更新考卷
     * @param int $id
     * @param PaperUpdateForm $form
     * @return array
     */
    public function update(int $id, PaperUpdateForm $form)
    {
        $input = $form->input();
        // 由于表结构有变化这里需要将字段做一些转换处理
        if (isset($input['front_cover_id'])) {
            $uploadFile = UploadFileModel::query()->find($input['front_cover_id']);
            if (!$uploadFile) throw new NotFoundHttpException('封面不存在或已被删除');
            $input['front_cover'] = $uploadFile->path;
        }

        if (isset($input['status'])) {
            $release = \Dic::getEntryIndex(DicEnum::PAPER_STATUS, DicEnum::PAPER_STATUS_RELEASE);
            $notRelease = \Dic::getEntryIndex(DicEnum::PAPER_STATUS, DicEnum::PAPER_STATUS_NOT_RELEASE);
            switch ($input['status']) {
                case $notRelease:
                    unset($input['status']);
                    break;
                case $release:
                    // 如果考卷状态被修改为发布，则生成考卷模板
                    $err = $this->paperService->generateTemplate($id);
                    if ($err) {
                        return ApiResponse::error('发布失败，请检查考卷信息是否填写完整', ErrorCodeEnum::CHECK_PAPER_COMPLETE_FAIL, $err);
                    }
                    break;
            }
        }

        $paper = $this->paperService->update($id, $input);
        return ApiResponse::success(new PaperUpdateResource($paper));
    }

    /**
     * 删除考卷
     * @param int $id
     * @return array
     * @throws \Exception
     */
    public function delete(int $id)
    {
        $this->paperService->delete($id);
        return ApiResponse::success();
    }

    /**
     * 获取详情
     * @param int $id
     * @return array
     */
    public function detail(int $id)
    {
        $paper = $this->paperService->getById($id);
        return ApiResponse::success(new PaperDetailResource($paper));
    }

    /**
     * 获取考卷分页列表
     * @param PaperPageForm $form
     * @return array
     */
    public function list(PaperPageForm $form)
    {
        $condition = $form->input('condition');
        $page = $form->input('page');
        $size = $form->input('size');
        $paperList = $this->paperService->getPageList($condition, $page, $size);

        return ApiResponse::success(PaperListResource::collection($paperList));
    }

    public function clonePaper(int $id)
    {
        $paperModel = $this->paperService->copy($id);
        return ApiResponse::success(new PaperCloneResource($paperModel));
    }

    public function getExamplePageList(PaperExamplePageForm $form)
    {
        $page = $form->input('page');
        $size = $form->input('size');
        $officialExamplePaperIds = config('system.paper.official_example');
        $paginate = PaperModel::query()
            ->withoutGlobalScopes()
            ->whereIn('id', $officialExamplePaperIds)
            ->whereStatus(PaperEnum::STATUS_RELEASE)
            ->skip($page)
            ->paginate($size);

        return ApiResponse::success(PaperExamplePageResource::collection($paginate));
    }
}