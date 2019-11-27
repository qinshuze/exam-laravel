<?php


namespace App\Http\Controllers\Api;


use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Forms\PaperTopic\ImportTopicByPaperForm;
use App\Http\Forms\PaperTopic\PaperTopicDeleteForm;
use App\Http\Forms\PaperTopic\PaperTopicImportForm;
use App\Http\Forms\PaperTopic\PaperTopicPageForm;
use App\Http\Forms\PaperTopic\PaperTopicSaveForm;
use App\Http\Forms\PaperTopic\PaperTopicWeightForm;
use App\Http\Resources\PaperTopic\PaperTopicCloneResource;
use App\Http\Resources\PaperTopic\PaperTopicPageResource;
use App\Http\Resources\PaperTopic\PaperTopicSaveResource;
use App\Http\Resources\PaperTopic\PaperTopicTypeQuantityResource;
use App\Jobs\TopicImportByExcel;
use App\Models\PaperConfigModel;
use App\Models\TopicTypeModel;
use App\Services\PaperService;
use Symfony\Component\CssSelector\Exception\InternalErrorException;

class PaperTopicController extends Controller
{
    /**
     * @var PaperService
     */
    private $paperService;

    /**
     * PaperTopicController constructor.
     * @param PaperService $paperService
     */
    public function __construct(PaperService $paperService)
    {
        $this->paperService = $paperService;
    }

    /**
     * 保存考卷题目
     * @param int $paper_id
     * @param PaperTopicSaveForm $form
     * @param int|null $topic_id
     * @return array
     * @throws \Exception
     */
    public function saveTopic(int $paper_id, PaperTopicSaveForm $form, ?int $topic_id = null)
    {
        if ($topic_id) {
            $topic = $this->paperService->updateTopic($paper_id, $topic_id, $form->input());
        }
        else {
            $this->paperService->addTopicNumber($paper_id);
            $topic = $this->paperService->addTopic($paper_id, $form->input());
        }

        $topic->paper_id = $paper_id;
        return ApiResponse::success(new PaperTopicSaveResource($topic));
    }

    /**
     * 获取考卷题目分页列表
     * @param int $paper_id
     * @param PaperTopicPageForm $form
     * @return array
     */
    public function getTopicPageList(int $paper_id, PaperTopicPageForm $form)
    {
        $condition = $form->input('condition');
        $page      = $form->input('page');
        $size      = $form->input('size');
        $data      = $this->paperService->getPaperTopicPageList($paper_id, $condition, $page, $size);
        return ApiResponse::success(PaperTopicPageResource::collection($data));
    }

    /**
     * 获取考卷总分
     * @param int $paper_id
     * @return array
     */
    public function getTotalScore(int $paper_id)
    {
        $totalScore = $this->paperService->getTotalScore($paper_id);
        return ApiResponse::success(['total_score' => $totalScore]);
    }

    /**
     * 删除考卷题目
     * @param int $paper_id
     * @param PaperTopicDeleteForm $form
     * @return array
     * @throws \Exception
     */
    public function deleteTopic(int $paper_id, PaperTopicDeleteForm $form)
    {
        $ids = $form->input('ids', []);
        $this->paperService->deleteTopic($paper_id, $ids);
        return ApiResponse::success();
    }

    public function importTopicByExcel(int $paper_id, PaperTopicImportForm $form)
    {
        $file = $form->file('excel');
        if (!$file->isValid()) throw new InternalErrorException('文件上传失败');

        $path            = date('Ymd') . '/' . md5($file->getClientOriginalName() . time()) . '.' . $file->getClientOriginalExtension();
        $uploadFileModel = \UploadFileService::uploadToLocal($file, $path, 'excel');

        $this->dispatch(new TopicImportByExcel($paper_id, $uploadFileModel->id));
        return ApiResponse::success();
    }

    public function importTopicByPaper(int $paper_id, ImportTopicByPaperForm $form)
    {
        $number = $this->paperService->importTopicByPaper($paper_id, $form->input());

        return ApiResponse::success(['insert' => $number]);
    }

    public function getTopicTypeQuantity(int $paper_id)
    {
        $paperConfigModel  = PaperConfigModel::query()->find($paper_id);
        $topicTypeQuantity = collect($this->paperService->getTopicTypeQuantity($paper_id))->keyBy('topic_type_id');
        $randomConfig      = collect($paperConfigModel->organization_method->random->config)->keyBy('topic_type_id');
        $topicType         = TopicTypeModel::query()->get();
        $res               = [];
        foreach ($topicType as $item) {
            $res['topic'][] = [
                'topic_type_id' => $item->id,
                'count'         => isset($topicTypeQuantity[$item->id]) ? $topicTypeQuantity[$item->id]->quantity : 0,
                'amount'        => isset($randomConfig[$item->id]) ? $randomConfig[$item->id]->quantity : 0,
                'score'         => isset($randomConfig[$item->id]) ? $randomConfig[$item->id]->score : 0,
                'missing_score' => isset($randomConfig[$item->id]) ? $randomConfig[$item->id]->missing_score : 0,
            ];
        }
        return ApiResponse::success($res);
    }

    public function generateRandomTopic(int $paper_id)
    {
        $randomTopicIds = $this->paperService->generateRandomTopic($paper_id);
        return ApiResponse::success($randomTopicIds);
    }

    public function cloneTopic(int $paper_id, int $topic_id)
    {
        $topicModel = $this->paperService->copyTopic($paper_id, $topic_id);
        return ApiResponse::success(new PaperTopicCloneResource($topicModel));
    }

    public function updateTopicWeight(int $paper_id, PaperTopicWeightForm $form)
    {
        $this->paperService->updateTopicWeight($paper_id, $form->input());
        return ApiResponse::success();
    }
}