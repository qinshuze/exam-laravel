<?php


namespace App\Services;


use App\Enums\DicEnum;
use App\Enums\ExceptionMessageEnum;
use App\Enums\PaperEnum;
use App\Models\PaperApplyModel;
use App\Models\PaperConfigModel;
use App\Models\PaperModel;
use App\Models\PaperTopicModel;
use App\Models\TopicModel;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PaperService
{
    /**
     * @var PaperConfigService
     */
    private $paperConfigService;
    /**
     * @var TopicService
     */
    private $topicService;
    /**
     * @var PaperApplyService
     */
    private $paperApplyService;

    /**
     * PaperServiceImpl constructor.
     * @param PaperConfigService $paperConfigService
     * @param TopicService $topicService
     * @param PaperApplyService $paperApplyService
     */
    public function __construct(PaperConfigService $paperConfigService, TopicService $topicService, PaperApplyService $paperApplyService)
    {
        $this->paperConfigService = $paperConfigService;
        $this->topicService       = $topicService;
        $this->paperApplyService  = $paperApplyService;
    }

    /**
     * 根据id获取考卷
     * @param int $id
     * @return PaperModel|PaperModel[]|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     */
    public function getById(int $id)
    {
        $model = PaperModel::query()->find($id);
        if (!$model) throw new NotFoundHttpException(ExceptionMessageEnum::PAPER_NOT_NULL);
        $model->front_cover  = $model->front_cover ? $model->front_cover : config('system.paper.default_front_cover');
        $model->topic_number = $this->getTopicQuantity([$id])[$id];
        return $model;
    }

    /**
     * 获取考卷分页列表
     * @param array|null $condition
     * @param int|null $page
     * @param int|null $size
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getPageList(?array $condition, ?int $page, ?int $size)
    {
        $queryBuilder = PaperModel::query();
        if (isset($condition['title'])) $queryBuilder->whereTitle($condition['title']);
        if (isset($condition['status'])) $queryBuilder->whereStatus($condition['status']);
        if (isset($condition['paper_type_id'])) $queryBuilder->wherePaperTypeId($condition['paper_type_id']);
        $paginate  = $queryBuilder->skip($page)->paginate($size);
        $paperList = $paginate->items();

        $paperIds           = array_column($paperList, 'id');
        $paperTopicQuantity = $this->getTopicQuantity($paperIds);
        foreach ($paperList as $paper) {
            $paper->topic_number = $paperTopicQuantity[$paper->id];
        }

        return $paginate;
    }

    public function getTopicQuantity(array $ids)
    {
        $paperTopicQuantity = PaperTopicModel::query()
            ->whereIn('paper_id', $ids)
            ->selectRaw('paper_id, count(1) quantity')
            ->groupBy('paper_id')->get()->keyBy('paper_id');

        $data = [];
        foreach ($ids as $id) {
            $data[$id] = $paperTopicQuantity[$id]->quantity ?? 0;
        }

        return $data;
    }

    /**
     * 创建考卷
     * @param array $data
     * @return PaperModel
     * @throws \Exception
     */
    public function create(array $data)
    {
        $model                = new PaperModel();
        $model->title         = $data['title'] ?? '未命名的';
        $model->front_cover   = $data['front_cover'] ?? '';
        $model->paper_type_id = $data['paper_type_id'];
        $model->status        = \Dic::getDefaultEntryIndex(DicEnum::PAPER_STATUS);
        $model->description   = $data['description'] ?? '';
        $model->answer_number = 0;
        $model->topic_number  = 0;
        $model->created_by    = \UserService::getCurrentUserId();

        \DB::beginTransaction();
        try {
            $model->save();
            $model->front_cover = $model->front_cover ? $model->front_cover : config('system.paper.default_front_cover');
            $this->paperConfigService->create($model->id, []);
            \DB::commit();
        } catch (\Exception $exception) {
            \DB::rollBack();
            throw $exception;
        }

        return $model;
    }

    /**
     * 更新考卷
     * @param int $id
     * @param array $data
     * @return PaperModel|PaperModel[]|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     */
    public function update(int $id, array $data)
    {
        $model = PaperModel::query()->find($id);
        if (!$model) throw new NotFoundHttpException('考卷不存在或已被删除');
        if (isset($data['title'])) $model->title = $data['title'];
        if (isset($data['front_cover'])) $model->front_cover = $data['front_cover'];
        if (isset($data['paper_type_id'])) $model->paper_type_id = $data['paper_type_id'];
        if (isset($data['status'])) $model->status = $data['status'];
        if (isset($data['description'])) $model->description = $data['description'];
        $model->update();

        $model->front_cover = $model->front_cover ? $model->front_cover : config('system.paper.default_front_cover');
        return $model;
    }

    /**
     * 删除考卷
     * @param int $id
     * @return bool|mixed|null
     * @throws \Exception
     */
    public function delete(int $id)
    {
        return PaperModel::query()->whereKey($id)->delete();
    }

    /**
     * 修改考卷状态为暂停
     * @param int $id
     */
    public function pause(int $id)
    {
        $this->setStatus($id, DicEnum::PAPER_STATUS_PAUSE);
    }

    /**
     * 修改考卷状态为发布
     * @param int $id
     */
    public function release(int $id)
    {
        $this->setStatus($id, DicEnum::PAPER_STATUS_RELEASE);
    }

    /**
     * 设置考卷状态
     * @param int $id
     * @param string $status
     * @return PaperModel|PaperModel[]|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     */
    public function setStatus(int $id, string $status)
    {
        $model = PaperModel::query()->find($id);
        if (!$model) throw new NotFoundHttpException('考卷不存在或已被删除');
        $model->status = \Dic::getEntryIndex(DicEnum::PAPER_STATUS, $status);
        $model->update();

        return $model;
    }

    /**
     * 生成考卷模板
     * @param int $id
     * @return array
     */
    public function generateTemplate(int $id)
    {
        $model = PaperModel::query()->find($id);
        if (!$model) throw new NotFoundHttpException('考卷不存在或已被删除');
        $paperConfig = $this->paperConfigService->getByPaperId($id);

        $paperTopics            = [];
        $examMode               = \Dic::getEntryIndex(DicEnum::PAPER_MODE, DicEnum::PAPER_MODE_EXAM);
        $randomMethod           = \Dic::getEntryIndex(DicEnum::PAPER_ORGANIZATION_METHOD, DicEnum::PAPER_ORGANIZATION_METHOD_RAND);
        $allMethod              = \Dic::getEntryIndex(DicEnum::PAPER_SCORE_CONFIG, DicEnum::PAPER_SCORE_CONFIG_CUSTOM);
        $standardType           = \Dic::getEntryIndex(DicEnum::PAPER_SCORE_CONFIG, DicEnum::PAPER_SCORE_CONFIG_STANDARD);
        $customType             = \Dic::getEntryIndex(DicEnum::PAPER_SCORE_CONFIG, DicEnum::PAPER_SCORE_CONFIG_CUSTOM);
        $visitRestrictionEnable = \Dic::getEntryIndex(DicEnum::PAPER_VISIT_RESTRICTION, DicEnum::PAPER_VISIT_RESTRICTION_ENABLE);
        $passStatus             = \Dic::getEntryIndex(DicEnum::PAPER_APPLY_STATUS, DicEnum::PAPER_APPLY_STATUS_PASS);

        if ($paperConfig->mode == $examMode) {
            $organization_method = $paperConfig->organization_method;
            $score_config        = array_column($paperConfig->score_config->standard, 'topic_type_id');
            $randomConfig        = array_column($organization_method->random->config, 'topic_type_id');

            // 如果组卷模式设置为随机抽题，当有设置了随机题目的分数时，则使用设置的随机题目分数，没有则使用分数设置的分数作为题目分数
            if ($organization_method->type == $randomMethod) {
                $paperTopics = $this->getPaperTopicByIds($id, $organization_method->random->topic);
                if ($paperConfig->score_config->type == $standardType) {
                    foreach ($paperTopics as $topic) {
                        $topic->score = $randomConfig[$topic->topic_type_id] ?? $score_config[$topic->topic_type_id];
                    }
                }
                elseif ($paperConfig->score_config->type == $customType) {
                    foreach ($paperTopics as $topic) {
                        $topic->score = $randomConfig[$topic->topic_type_id] ?? $topic->score;
                    }
                }
            }
            elseif ($organization_method->type == $allMethod) {
                // 当分数设置为自定义分数，并且随机抽题中没有设置题型分数，则使用自定义分数
                $paperTopics = $this->getPaperTopicList($id);
                if ($paperConfig->score_config->type == $standardType) {
                    foreach ($paperTopics as $topic) {
                        $topic->score = $score_config[$topic->topic_type_id];
                    }
                }
            }
        }

        // 访问限制
        $allowAnswerUsers = [];
        if ($paperConfig->visit_restriction == $visitRestrictionEnable) {
            $allowAnswerUsers = PaperApplyModel::query()->whereUserId(\UserService::getCurrentUserId())->wherePaperId($id)->whereStatus($passStatus)->get()->pluck('user_id')->toArray();
        }

        $totalScore  = 0;
        $topicNumber = 0;
        foreach ($paperTopics as $topic) {
            unset($topic->created_at);
            unset($topic->updated_at);
            unset($topic->created_by);
            $totalScore += $topic->score;
            $topicNumber++;
        }

        $paperTemplate = [
            'id'                     => $model->id,
            'title'                  => $model->title,
            'front_cover'            => $model->front_cover,
            'paper_type_id'          => $model->paper_type_id,
            'status'                 => $model->status,
            'limited_time'           => $paperConfig->limited_time,
            'archives'               => $paperConfig->archives,
            'custom_archives'        => $paperConfig->custom_archives,
            'answer_frequency'       => $paperConfig->answer_frequency,
            'allow_answer_users'     => $allowAnswerUsers,
            'visit_password'         => $paperConfig->visit_password,
            'topic_type_description' => $paperConfig->topic_type_description,
            'mode'                   => $paperConfig->mode,
            'validity_period'        => $paperConfig->validity_period,
            'organization_method'    => $paperConfig->organization_method,
            'topic_number'           => $topicNumber,
            'total_score'            => $totalScore,
            'topic'                  => $paperTopics,
        ];

        $errTopic = $this->topicService->checkIntegrity($paperTopics->toArray());
        if ($errTopic) {
            return $errTopic;
        }

        \Storage::disk('paper')->put($model->id . '.json', json_encode($paperTemplate));
    }

    /**
     * 增加考卷题目数量
     * @param int $id
     * @param int $number
     * @return mixed
     */
    public function addTopicNumber(int $id, int $number = 1)
    {
        return $this->increment($id, 'topic_number', $number);
    }

    /**
     * 增加考卷答题人数
     * @param int $id
     * @param int $number
     * @return mixed
     */
    public function addAnswerNumber(int $id, int $number = 1)
    {
        return $this->increment($id, 'answer_number', $number);
    }

    /**
     * 字段自增
     * @param int $id
     * @param string $column
     * @param int $number
     * @return mixed
     */
    public function increment(int $id, string $column, int $number)
    {
        $model = PaperModel::query()->find($id);
        if (!$model) throw new NotFoundHttpException('考卷不存在或已被删除');
        $model->{$column} += $number;
        $model->update();
        return $model->{$column};
    }

    /**
     * @param int $id
     * @return float|int|mixed
     */
    public function getTotalScore(int $id)
    {
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
            $scoreConfig = $paperConfig->organization_method->random->config;
            foreach ($scoreConfig as $item) {
                $topicTypeScore = array_column($paperConfig->score_config->standard, 'topic_type_id');
                $score          = $item->score ?? $topicTypeScore[$item['topic_type_id']]['score'] ?? 0;
                $total          += ($item->quantity * $score);
            }
        }
        elseif ($paperConfig->organization_method->type == $all) {
            if ($paperConfig->score_config->type == $standard) {
                $topicTypeQuantity = $this->getTopicTypeQuantity($id);
                $topicTypeScore    = array_column($paperConfig->score_config->standard, 'score', 'topic_type_id');
                foreach ($topicTypeQuantity as $item) {
                    $total += $topicTypeScore[$item['topic_type_id']] ?? 0;
                }
            }
            elseif ($paperConfig->score_config->type == $custom) {
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
    public function getPaperTopicPageList(int $id, ?array $condition = [], ?int $page = 1, ?int $size = 15)
    {
        $paperConfigModel = PaperConfigModel::query()->find($id);
        $paperTopicList   = PaperModel::query()
            ->join('paper_topic', 'paper_topic.paper_id', '=', 'paper.id')
            ->join('topic', function ($join) use ($condition) {
                $join->on('topic.id', '=', 'paper_topic.topic_id');
                if (isset($condition['content'])) $join->where('topic.content', $condition['content']);
            })
            ->where('paper.id', $id)
            ->skip($page)
            ->selectRaw('topic.*,paper_topic.score,paper_topic.weight,paper_topic.paper_id')
            ->paginate($size);

        if ($paperConfigModel->score_config->type == PaperEnum::SCORE_TYPE_NOT) {
            foreach ($paperTopicList as $item) {
                $item->score = 0;
            }
        }
        else if ($paperConfigModel->score_config->type == PaperEnum::SCORE_TYPE_STANDARD) {
            $standard = array_column($paperConfigModel->score_config->standard, 'score', 'topic_type_id');
            foreach ($paperTopicList as $item) {
                $item->score = $standard[$item->topic_type_id] ?? 0;
            }
        }

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
            ->join('paper_topic', 'paper_topic.paper_id', '=', 'paper.id')
            ->join('topic', function ($join) use ($ids) {
                $join->on('topic.id', '=', 'paper_topic.topic_id')->whereIn('topic.id', $ids);
            })
            ->where('paper.id', $id)
            ->selectRaw('topic.*,paper_topic.score,paper_topic.weight')
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
            ->selectRaw('topic.*, paper_topic.score, paper_topic.weight')
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
            ->join('paper_topic', 'paper_topic.paper_id', '=', 'paper.id')
            ->join('topic', 'topic.id', '=', 'paper_topic.topic_id')
            ->where('paper.id', $id)
            ->whereNull('paper.deleted_at')
            ->selectRaw('topic.topic_type_id, count(1) quantity')
            ->groupBy('topic.topic_type_id')
            ->get();
        return $topicTypeQuantity;
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
        $model = PaperModel::query()->find($id);
        if (!$model) throw new NotFoundHttpException('考卷不存在或已被删除');
        $paperConfigModel = $this->paperConfigService->getByPaperId($id);

        \DB::beginTransaction();
        try {
            $weight                    = PaperTopicModel::query()->wherePaperId($id)->max('weight') ?? 0;
            $topicModel                = $this->topicService->create($data);
            $paperTopicModel           = new PaperTopicModel();
            $paperTopicModel->paper_id = $id;
            $paperTopicModel->topic_id = $topicModel->id;
            $customType                = \Dic::getEntryIndex(DicEnum::PAPER_SCORE_CONFIG, DicEnum::PAPER_SCORE_CONFIG_CUSTOM);
            // 只有当分数配置设置为自定义分数的情况下，才允许对题目分数赋值
            if ($paperConfigModel->score_config->type == $customType) {
                $paperTopicModel->score = $data['score'] ?? 0;
            }
            else {
                $paperTopicModel->score = 0;
            }
            $paperTopicModel->weight = $weight + 1;
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
        $model = PaperModel::query()->find($id);
        if (!$model) throw new NotFoundHttpException('考卷不存在或已被删除');
        $paperConfigModel = $this->paperConfigService->getByPaperId($id);
        $paperTopicModel  = PaperTopicModel::query()->wherePaperId($id)->whereTopicId($topic_id)->first();
        if (!$paperTopicModel) throw new NotFoundHttpException(ExceptionMessageEnum::TOPIC_NOT_NULL);

        \DB::beginTransaction();
        try {
            $topicModel = $this->topicService->update($topic_id, $data);
            $customType = \Dic::getEntryIndex(DicEnum::PAPER_SCORE_CONFIG, DicEnum::PAPER_SCORE_CONFIG_CUSTOM);
            // 只有当分数配置设置为自定义分数的情况下，才允许对题目分数赋值
            if ($paperConfigModel->score_config->type == $customType) $paperTopicModel->score = $data['score'] ?? 0;
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
     * @param array $topic_ids
     * @return bool|mixed|null
     * @throws \Exception
     */
    public function deleteTopic(int $id, array $topic_ids)
    {
        return PaperTopicModel::query()->wherePaperId($id)->whereIn('topic_id', $topic_ids)->delete();
    }

    public function generateRandomTopic(int $id)
    {
        $model = PaperModel::query()->find($id);
        if (!$model) throw new NotFoundHttpException('考卷不存在或已被删除');
        $randomType          = \Dic::getEntryIndex(DicEnum::PAPER_ORGANIZATION_METHOD, DicEnum::PAPER_ORGANIZATION_METHOD_RAND);
        $paperConfig         = $this->paperConfigService->getByPaperId($id);
        $organization_method = $paperConfig->organization_method;

        if ($organization_method->type != $randomType) {
            throw new BadRequestHttpException('当前组卷方式不支持此操作');
        }

        $randomTopicIds = [];
        foreach ($organization_method->random->config as $config) {
            if (empty($config->quantity)) continue;
            $topics         = PaperTopicModel::query()
                ->join('topic', function ($join) use ($config) {
                    $join->on('topic.id', '=', 'paper_topic.topic_id')->where('topic_type_id', $config->topic_type_id);
                })
                ->wherePaperId($id)
                ->inRandomOrder()
                ->limit($config->quantity)
                ->select('topic.id')
                ->pluck('id')->toArray();
            $randomTopicIds = array_merge($randomTopicIds, $topics);
        }

        $organization_method->random->topic = $randomTopicIds;
        $paperConfig->organization_method   = $organization_method;
        $paperConfig->save();

        return $randomTopicIds;
    }

    /**
     * 复制考卷
     * @param int $id
     * @return PaperModel|\Illuminate\Database\Eloquent\Model
     * @throws \Exception
     */
    public function copy(int $id)
    {
        $model = PaperModel::query()->find($id);
        if (!$model) throw new NotFoundHttpException('考卷不存在或已被删除');
        $paperConfigModel = $this->paperConfigService->getByPaperId($id);
        $allowClone       = \Dic::getEntryIndex(DicEnum::PAPER_IS_ALLOW_CLONE, DicEnum::PAPER_IS_ALLOW_CLONE_ALLOW);
        if ($paperConfigModel->is_allow_clone != $allowClone) {
            throw new BadRequestHttpException('当前考卷不允许进行此操作');
        }

        \DB::beginTransaction();
        try {
            $count            = PaperModel::query()->whereRaw("title REGEXP '{$model->title} \\\([0-9]+\\\)'")->count();
            $newModel         = $model->replicate();
            $newModel->title  = $model->title . " ({$count})";
            $newModel->status = \Dic::getDefaultEntryIndex(DicEnum::PAPER_STATUS);
            $newModel->save();

            $newPaperConfigModel           = $paperConfigModel->replicate();
            $newPaperConfigModel->paper_id = $newModel->id;
            $newPaperConfigModel->save();

            $paperTopicModel  = PaperTopicModel::query()->wherePaperId($id)->get();
            $insertPaperTopic = [];
            foreach ($paperTopicModel as $item) {
                $insertPaperTopic[] = [
                    'paper_id' => $newModel->id,
                    'topic_id' => $item->topic_id,
                    'score'    => $item->score,
                    'weight'   => $item->weight,
                ];
            }

            PaperTopicModel::query()->insert($insertPaperTopic);
            \DB::commit();

        } catch (\Exception $exception) {
            \DB::rollBack();
            throw $exception;
        }

        return $newModel;
    }

    /**
     * 复制题目
     * @param int $id
     * @param int $topic_id
     * @return TopicModel|TopicModel[]|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     * @throws \Exception
     */
    public function copyTopic(int $id, int $topic_id)
    {
        $paperModel = PaperModel::query()->find($id);
        if (!$paperModel) throw new NotFoundHttpException('考卷不存在或已被删除');
        $paperTopicModel = PaperTopicModel::query()->wherePaperId($id)->whereTopicId($topic_id)->first();
        if (!$paperTopicModel) throw new NotFoundHttpException('题目不存在或已被删除');
        $topicModel = $this->topicService->getById($paperTopicModel->topic_id);
        if (!$topicModel) throw new NotFoundHttpException('题目不存在或已被删除');

        \DB::beginTransaction();
        try {
            $count                  = TopicModel::query()->whereRaw("content REGEXP '{$topicModel->content} \\\([0-9]+\\\)'")->count();
            $newTopicModel          = $topicModel->replicate();
            $newTopicModel->content = $topicModel->content . " ({$count})";
            $newTopicModel->save();

            PaperTopicModel::query()->where('paper_id', $id)->where('weight', '>', $paperTopicModel->weight)->increment('weight');
            $newPaperTopic           = $paperTopicModel->replicate();
            $newPaperTopic->topic_id = $newTopicModel->id;
            $newPaperTopic->weight   = $paperTopicModel->weight + 1;
            $newPaperTopic->save();

            $newTopicModel->score    = $newPaperTopic->score;
            $newTopicModel->weight   = $newPaperTopic->weight;
            $newTopicModel->paper_id = $id;

            \DB::commit();
            return $newTopicModel;
        } catch (\Exception $exception) {
            \DB::rollBack();
            throw $exception;
        }
    }

    public function setTopicWeight(int $id, array $data)
    {
        foreach ($data as $item) {
            PaperTopicModel::query()->wherePaperId($id)->whereTopicId($item['topic_id'])->update(['weight' => $item['weight']]);
        }
    }

    public function importTopicByPaper(int $id, array $data)
    {
        $paperModel = PaperModel::query()->find($id);
        if (!$paperModel) throw new NotFoundHttpException('考卷不存在或已被删除');

        $data           = collect($data);
        $sourcePaperIds = $data->pluck('paper_id')->toArray();
        $sourceTopicIds = $data->pluck('topic_id')->toArray();

        // 过滤部不属于当前用户的考卷id
        $sourcePaperIds = PaperModel::query()->whereIn('id', $sourcePaperIds)->pluck('id')->toArray();

        // 获取源考卷题目数据
        $sourcePaperTopicModelList = PaperTopicModel::query()->whereIn('paper_id', $sourcePaperIds)->whereIn('topic_id', $sourceTopicIds)->get()->keyBy('topic_id');
        $sourcePaperTopicIds       = $sourcePaperTopicModelList->pluck('topic_id')->toArray();
        if (!$sourcePaperTopicIds) return 0;

        // 过滤掉目标考卷中已存在的题目
        $newPaperTopic  = PaperTopicModel::query()->wherePaperId($id)->whereIn('topic_id', $sourcePaperTopicIds)->get();
        $insertTopicIds = array_diff($sourcePaperTopicIds, $newPaperTopic->pluck('topic_id')->toArray());
        if (!$insertTopicIds) return 0;

        $weight           = PaperTopicModel::query()->wherePaperId($id)->max('weight') ?? 0;
        $insertPaperTopic = [];
        foreach ($insertTopicIds as $tid) {
            $tmp = $sourcePaperTopicModelList->get($tid);
            if (!$tmp) continue;
            $insertPaperTopic[] = [
                'paper_id' => $id,
                'topic_id' => $tmp->topic_id,
                'score'    => $tmp->score,
                'weight'   => ++$weight
            ];
        }

        PaperTopicModel::query()->insert($insertPaperTopic);
        return count($insertTopicIds);
    }

    public function updateTopicWeight(int $id, array $data)
    {
        $model = PaperModel::query()->find($id);
        if (!$model) throw new NotFoundHttpException('考卷不存在或已被删除');
        \DB::beginTransaction();
        try {
            foreach ($data as $item) {
                PaperTopicModel::query()->wherePaperId($model->id)->whereTopicId($item['topic_id'])->update(['weight' => $item['weight']]);
            }
            \DB::commit();
        } catch (\Exception $exception) {
            \DB::rollBack();
            throw $exception;
        }
    }
}