<?php


namespace App\Services;


use App\Enums\DicEnum;
use App\Enums\ExceptionMessageEnum;
use App\Models\PaperModel;
use App\Models\PaperTopicModel;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PaperTopicService
{
    /**
     * @var PaperService
     */
    private $paperService;
    /**
     * @var PaperConfigService
     */
    private $paperConfigService;
    /**
     * @var TopicService
     */
    private $topicService;

    /**
     * @param int $id
     * @return float|int|mixed
     */
    public function getTotalScore(int $id)
    {
        $model = $this->paperService->getById($id);
        if (!$model) return 0;

        $total       = 0;
        $paperConfig = $this->paperConfigService->getByPaperId($id);
        $notScore    = \Dic::getEntryIndex(DicEnum::PAPER_SCORE_CONFIG, DicEnum::PAPER_SCORE_CONFIG_NOT);
        $standard    = \Dic::getEntryIndex(DicEnum::PAPER_SCORE_CONFIG, DicEnum::PAPER_SCORE_CONFIG_STANDARD);
        $custom      = \Dic::getEntryIndex(DicEnum::PAPER_SCORE_CONFIG, DicEnum::PAPER_SCORE_CONFIG_CUSTOM);
        $brush       = \Dic::getEntryIndex(DicEnum::PAPER_MODE, DicEnum::PAPER_MODE_BRUSH);
        $random      = \Dic::getEntryIndex(DicEnum::PAPER_ORGANIZATION_METHOD, DicEnum::PAPER_ORGANIZATION_METHOD_RAND);
        $all         = \Dic::getEntryIndex(DicEnum::PAPER_ORGANIZATION_METHOD, DicEnum::PAPER_ORGANIZATION_METHOD_ALL);

        // 如果是刷题模式或者分数配置设置为无分数，则直接返回0分
        if ($paperConfig->mode == $brush || $paperConfig->score_config->type == $notScore) {
            return 0;
        }

        // 如果是随机抽题模式，则统计生成的随机题目的分数
        if ($paperConfig->organization_method->type == $random) {
            $scoreConfig = $paperConfig->organization_method->random;
            foreach ($scoreConfig as $item) {
                $topicTypeScore = array_column($paperConfig->score_config->topic, 'topic_type_id');
                $score          = $item->score ?? $topicTypeScore[$item['topic_type_id']]['score'] ?? 0;
                $total          += ($item->amount * $score);
            }
        } elseif ($paperConfig->organization_method->type == $all) {
            if ($paperConfig->score_config->type == $standard) {
                $topicTypeQuantity = $this->getTopicTypeQuantity($id);
                $topicTypeScore    = array_column($paperConfig->score_config->topic, 'topic_type_id');
                foreach ($topicTypeQuantity as $item) {
                    $total += $topicTypeScore[$item['topic_type_id']]['score'];
                }
            } elseif ($paperConfig->score_config->type == $custom) {
                $total = PaperTopicModel::query()->wherePaperId($id)->sum('score');
            }
        }

        return $total;
    }

    /**
     * 获取考卷题目分页列表
     * @param int $id
     * @param array $condition
     * @param int $page
     * @param int $size
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getPaperTopicPageList(int $id, array $condition = [], int $page = 1, int $size = 15)
    {
        $paperTopicList = PaperModel::query()
            ->join('paper_topic', 'paper_topic.paper_id', '=', $id)
            ->join('topic', function ($join) use ($condition) {
                $join->on('topic.id', '=', 'paper_topic.topic_id');
                if (isset($condition['content'])) $join->where('topic.content', $condition['content']);
            })
            ->whereId($id)
            ->skip($page)
            ->select('topic.*,paper_topic.score,paper_topic.weight')
            ->paginate($size);
        return $paperTopicList;
    }

    /**
     * 根据题目id考卷题目
     * @param int $id
     * @param array $ids
     * @return PaperTopicModel[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Query\Builder[]|\Illuminate\Support\Collection
     */
    public function getPaperTopicByIds(int $id, array $ids)
    {
        $paperTopicList = PaperModel::query()
            ->join('paper_topic', 'paper_topic.paper_id', '=', $id)
            ->join('topic', function ($join) use ($ids) {
                $join->on('topic.id', '=', 'paper_topic.topic_id')->whereIn('topic.id', $ids);
            })
            ->whereId($id)
            ->select('topic.*,paper_topic.score,paper_topic.weight')
            ->get();
        return $paperTopicList;
    }

    /**
     * 获取考卷题目列表
     * @param int $id
     * @return PaperTopicModel[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Query\Builder[]|\Illuminate\Support\Collection
     */
    public function getPaperTopicList(int $id)
    {
        $paperTopicList = PaperModel::query()
            ->join('paper_topic', 'paper_topic.paper_id', '=', $id)
            ->join('topic', 'topic.id', '=', 'paper_topic.topic_id')
            ->whereId($id)
            ->whereNull('deleted_at')
            ->select('topic.*,paper_topic.score,paper_topic.weight')
            ->get();
        return $paperTopicList;
    }

    /**
     * 获取考卷题目类型数量
     * @param int $id
     * @return array
     */
    public function getTopicTypeQuantity(int $id)
    {
        $topicTypeQuantity = PaperModel::query()
            ->join('paper_topic', 'paper_topic.paper_id', '=', $id)
            ->join('topic', 'topic.id', '=', 'paper_topic.topic_id')
            ->whereId($id)
            ->whereNull('deleted_at')
            ->select('topic_type_id, count(1) quantity')
            ->groupBy('topic_type_id')
            ->get();
        return $topicTypeQuantity->toArray();
    }

    /**
     * 添加考卷题目
     * @param int $id
     * @param array $data
     * @return \App\Models\TopicModel
     * @throws \Exception
     */
    public function addTopic(int $id, array $data)
    {
        $model = $this->paperService->getById($id);
        if (!$model) throw new NotFoundHttpException(ExceptionMessageEnum::PAPER_NOT_NULL);

        \DB::beginTransaction();
        try {
            $weight                    = PaperTopicModel::query()->wherePaperId($id)->max('weight') ?? 0;
            $topicModel                = $this->topicService->create($data);
            $paperTopicModel           = new PaperTopicModel();
            $paperTopicModel->paper_id = $id;
            $paperTopicModel->topic_id = $topicModel->id;
            $paperTopicModel->score    = $data['score'] ?? 0;
            $paperTopicModel->weight   = $weight + 1;
            $paperTopicModel->save();

            $topicModel->score  = $paperTopicModel->score;
            $topicModel->weight = $paperTopicModel->weight;

            \DB::commit();
            return $topicModel;
        } catch (\Exception $exception) {
            \DB::rollBack();
            throw $exception;
        }
    }

    /**
     * 更新考卷题目
     * @param int $id
     * @param int $topic_id
     * @param array $data
     * @return TopicService|TopicService[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     * @throws \Exception
     */
    public function updateTopic(int $id, int $topic_id, array $data)
    {
        $model = $this->paperService->getById($id);
        if (!$model) throw new NotFoundHttpException(ExceptionMessageEnum::PAPER_NOT_NULL);
        $paperTopicModel = PaperTopicModel::query()->wherePaperId($id)->whereTopicId($topic_id)->first();
        if (!$paperTopicModel) throw new NotFoundHttpException(ExceptionMessageEnum::TOPIC_NOT_NULL);

        \DB::beginTransaction();
        try {
            $topicModel             = $this->topicService->update($topic_id, $data);
            $paperTopicModel->score = $data['score'] ?? 0;
            $paperTopicModel->save();

            $topicModel->score  = $paperTopicModel->score;
            $topicModel->weight = $paperTopicModel->weight;

            \DB::commit();
            return $topicModel;
        } catch (\Exception $exception) {
            \DB::rollBack();
            throw $exception;
        }
    }

    /**
     * 删除考卷题目
     * @param int $id
     * @param int $topic_id
     * @return bool|mixed|null
     * @throws \Exception
     */
    public function deleteTopic(int $id, int $topic_id)
    {
        return PaperTopicModel::query()->wherePaperId($id)->whereTopicId($topic_id)->delete();
    }
}