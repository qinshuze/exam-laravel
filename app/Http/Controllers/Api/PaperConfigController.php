<?php


namespace App\Http\Controllers\Api;


use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Forms\PaperConfig\PaperConfigUpdateForm;
use App\Http\Resources\PaperConfig\PaperConfigDetailResource;
use App\Http\Resources\PaperConfig\PaperConfigUpdateResource;
use App\Services\PaperConfigService;
use App\Services\PaperService;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PaperConfigController extends Controller
{
    /**
     * @var PaperConfigService
     */
    private $paperConfigService;
    /**
     * @var PaperService
     */
    private $paperService;

    /**
     * PaperConfigController constructor.
     * @param PaperConfigService $paperConfigService
     * @param PaperService $paperService
     */
    public function __construct(PaperConfigService $paperConfigService, PaperService $paperService)
    {
        $this->paperConfigService = $paperConfigService;
        $this->paperService = $paperService;
    }


    public function detail(int $paper_id)
    {
        $paperModel = $this->paperService->getById($paper_id);
        $paperConfigModel = $this->paperConfigService->getByPaperId($paper_id);
        if (!$paperConfigModel) throw new NotFoundHttpException('配置不存在或已被删除');
        $paperConfigModel->description = $paperModel->description;
        return ApiResponse::success(new PaperConfigDetailResource($paperConfigModel));
    }

    public function update(int $paper_id, PaperConfigUpdateForm $form)
    {
        $paperModel = $this->paperService->getById($paper_id);

        $input = $form->input();
        $topic_description = $input['topic_type_description'] ?? [];
        $score_config_topic = $input['score_config']['topic'] ?? [];

        $input['topic_type_description'] = [];
        foreach ($topic_description as $key => $value) {
            $input['topic_type_description'][] = [
                'topic_type_id' => $key,
                'description' => $value,
            ];
        }

        $input['score_config'] = [
            'type' => $input['score_config']['type']??''
        ];
        foreach ($score_config_topic as $key => $value) {
            $input['score_config']['standard'][] = [
                'topic_type_id' => $key,
                'score' => $value,
            ];
        }

        $randomConfig = &$input['organization_method']['random']['config'] ?? [];
        foreach ($randomConfig??[] as &$item) {
            if (isset($item['amount'])) {
                $item['quantity'] = $item['amount'];
                unset($item['amount']);
            }
        }

        $paperConfigModel = $this->paperConfigService->update($paper_id, $input);
        $tmp = $paperConfigModel->topic_type_description;
        $newTopicTypeDescription = [];
        foreach ($tmp as $item) {
            $newTopicTypeDescription[$item->topic_type_id] = $item->description;
        }

        $newScoreConfig = ['type'=>$paperConfigModel->score_config->type, 'topic'=>[]];
        $tmp = $paperConfigModel->score_config->standard;
        foreach ($tmp as $item) {
            $newScoreConfig['topic'][$item->topic_type_id] = $item->score;
        }

        $newOrganizationMethod = $paperConfigModel->organization_method;
        foreach ($newOrganizationMethod->random->config as &$item) {
            if (isset($item->quantity)) {
                $item->amount = $item->quantity;
                unset($item->quantity);
            }
        }

        $paperConfigModel->topic_type_description = $newTopicTypeDescription;
        $paperConfigModel->score_config = $newScoreConfig;
        $paperConfigModel->organization_method = $newOrganizationMethod;
        $paperConfigModel->description = $paperModel->description;
        return ApiResponse::success(new PaperConfigUpdateResource($paperConfigModel));
    }
}