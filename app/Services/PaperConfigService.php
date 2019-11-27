<?php


namespace App\Services;


use App\Enums\DicEnum;
use App\Enums\ExceptionMessageEnum;
use App\Enums\PaperModeEnum;
use App\Models\PaperConfigModel;
use App\Models\PaperModel;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PaperConfigService
{

    /**
     * 创建配置
     * @param int $paper_id
     * @param array $data
     * @return array
     */
    public function create(int $paper_id, array $data): array
    {
        if (!PaperModel::query()->find($paper_id)) {
            throw new NotFoundHttpException(ExceptionMessageEnum::PAPER_NOT_NULL);
        }

        $default_type        = \Dic::getDefaultEntryIndex(DicEnum::PAPER_ORGANIZATION_METHOD);
        $organization_method = [
            'type'   => $data['organization_method']['type'] ?? $default_type,
            'random' => [
                'config' => $data['organization_method']['random']['config'] ?? [],
                'topic'  => [],
            ],
        ];

        $default_type = \Dic::getDefaultEntryIndex(DicEnum::PAPER_SCORE_CONFIG);
        $score_config = [
            'type'     => $data['score_config']['type'] ?? $default_type,
            'standard' => $data['score_config']['standard'] ?? [],
        ];

        $validity_period = [
            'status'     => $data['validity_period']['status'] ?? false,
            'start_time' => $data['validity_period']['start_time'] ?? 0,
            'end_time'   => $data['validity_period']['end_time'] ?? 0,
        ];

        $custom_archives = [];
        foreach ($data['custom_archives'] ?? [] as $index  => $item) {
            $item['id'] = $index+1;
            $custom_archives[] = $item;
        }

        $model                         = new PaperConfigModel();
        $model->paper_id               = $paper_id;
        $model->mode                   = $data['mode'] ?? \Dic::getDefaultEntryIndex(PaperModeEnum::NAME);
        $model->is_show_result         = $data['is_show_result'] ?? \Dic::getDefaultEntryIndex(DicEnum::PAPER_SHOW_RESULT);
        $model->is_open                = $data['is_open'] ?? \Dic::getDefaultEntryIndex(DicEnum::PAPER_IS_OPEN);
        $model->is_allow_clone         = $data['is_allow_clone'] ?? \Dic::getDefaultEntryIndex(DicEnum::PAPER_IS_ALLOW_CLONE);
        $model->visit_restriction      = $data['visit_restriction'] ?? \Dic::getDefaultEntryIndex(DicEnum::PAPER_VISIT_RESTRICTION);
        $model->visit_password         = $data['visit_password'] ?? '';
        $model->validity_period        = $validity_period;
        $model->limited_time           = $data['limited_time'] ?? 0;
        $model->pass_score             = $data['pass_score'] ?? 0;
        $model->answer_frequency       = $data['answer_frequency'] ?? 0;
        $model->organization_method    = $organization_method;
        $model->applet_config          = $data['applet_config'] ?? [];
        $model->score_config           = $score_config;
        $model->topic_type_description = $data['topic_type_description'] ?? [];
        $model->archives               = $data['archives'] ?? [];
        $model->custom_archives        = $custom_archives;
        $model->save();

        return $model->toArray();
    }

    /**
     * 更新考卷配置
     * @param int $paper_id
     * @param array $data
     * @return PaperConfigModel|PaperConfigModel[]|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     */
    public function update(int $paper_id, array $data)
    {
        $model = $this->getByPaperId($paper_id);
        if (!$model) throw new NotFoundHttpException(ExceptionMessageEnum::PAPER_NOT_NULL);

        $custom_archives = [];
        foreach ($data['custom_archives'] ?? [] as $index  => $item) {
            $item['id'] = $index+1;
            $custom_archives[] = $item;
        }

        if (isset($data['mode'])) $model->mode = $data['mode'];
        if (isset($data['is_show_result'])) $model->is_show_result = $data['is_show_result'];
        if (isset($data['is_allow_clone'])) $model->is_allow_clone = $data['is_allow_clone'];
        if (isset($data['visit_restriction'])) $model->visit_restriction = $data['visit_restriction'];
        if (isset($data['visit_password'])) $model->visit_password = $data['visit_password'];
        if (isset($data['limited_time'])) $model->limited_time = $data['limited_time'];
        if (isset($data['pass_score'])) $model->pass_score = $data['pass_score'];
        if (isset($data['answer_frequency'])) $model->answer_frequency = $data['answer_frequency'];
        if (isset($data['topic_type_description'])) $model->topic_type_description = $data['topic_type_description'];
        if (isset($data['archives'])) $model->archives = $data['archives'];
        if (isset($data['custom_archives'])) $model->custom_archives = $custom_archives;
        if (isset($data['applet_config'])) $model->applet_config = $data['applet_config'];

        $validity_period = $model->validity_period;
        if (isset($data['validity_period']['status'])) $validity_period->status = $data['validity_period']['status'];
        if (isset($data['validity_period']['start_time'])) $validity_period->start_time = $data['validity_period']['start_time'];
        if (isset($data['validity_period']['end_time'])) $validity_period->end_time = $data['validity_period']['end_time'];

        $organization_method = $model->organization_method;
        if (isset($data['organization_method']['type'])) $organization_method->type = $data['organization_method']['type'];
        if (isset($data['organization_method']['random']['config'])) $organization_method->random->config = $data['organization_method']['random']['config'];
//        if (isset($data['organization_method']['random']['topic'])) $organization_method->random->topic = $data['organization_method']['random']['topic'];

        $score_config = $model->score_config;
        if (isset($data['score_config']['type'])) $score_config->type = $data['score_config']['type'];
        if (isset($data['score_config']['standard'])) $score_config->standard = $data['score_config']['standard'];

        $model->validity_period     = $validity_period;
        $model->organization_method = $organization_method;
        $model->score_config        = $score_config;
        $model->update();

        return $model;
    }

    /**
     * 删除配置
     * @param int $paper_id
     * @return mixed
     * @throws \Exception
     */
    public function delete(int $paper_id)
    {
        return PaperConfigModel::query()->whereKey($paper_id)->delete();
    }

    /**
     * 通过考卷id获取配置
     * @param int $paper_id
     * @return PaperConfigModel|PaperConfigModel[]|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     */
    public function getByPaperId(int $paper_id)
    {
        return PaperConfigModel::query()->find($paper_id);
    }
}