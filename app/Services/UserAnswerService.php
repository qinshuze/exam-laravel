<?php


namespace App\Services;


use App\Enums\PaperEnum;
use App\Enums\TopicTypeEnum;
use App\Enums\UserAnswerEnum;
use App\Models\PaperApplyModel;
use App\Models\PaperConfigModel;
use App\Models\PaperModel;
use App\Models\PaperTopicModel;
use App\Models\UserAnswerHistoryModel;
use App\Models\UserAnswerModel;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Symfony\Component\CssSelector\Exception\InternalErrorException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserAnswerService
{
    /**
     * @var PaperConfigService
     */
    private $paperConfigService;
    /**
     * @var PaperApplyService
     */
    private $paperApplyService;

    /**
     * UserAnswerService constructor.
     * @param PaperConfigService $paperConfigService
     * @param PaperApplyService $paperApplyService
     */
    public function __construct(PaperConfigService $paperConfigService, PaperApplyService $paperApplyService)
    {
        $this->paperConfigService = $paperConfigService;
        $this->paperApplyService  = $paperApplyService;
    }

    public function getUserAnswer(int $user_id, int $paper_id)
    {
        return UserAnswerModel::query()->whereUserId($user_id)->wherePaperId($paper_id)->first();
    }

    public function checkPaperStatus(PaperModel $paperModel)
    {
        switch ($paperModel->status) {
            case PaperEnum::STATUS_NOT_RELEASE:
                throw new AuthenticationException('该考试出题者还未发布');
            case PaperEnum::STATUS_PAUSE:
                throw new AuthorizationException('考试已被出题者暂停');
        }
    }

    public function checkVisitStatus(PaperApplyModel $paperApplyModel, PaperConfigModel $paperConfigModel)
    {
        if ($paperConfigModel->visit_restriction == PaperEnum::VISIT_RESTRICTION_ENABLE) {
            switch ($paperApplyModel->status) {
                case PaperEnum::APPLY_STATUS_WAIT:
                    throw new AuthenticationException('审批还未通过，请耐心等待');
                case PaperEnum::APPLY_STATUS_NOT_PASS:
                    throw new AuthorizationException('审批被拒绝');
            }
        }
    }

    public function checkPaperValidityPeriod(PaperConfigModel $paperConfigModel)
    {
        $time = time();
        if ($paperConfigModel->validity_period->status) {
            if ($time < $paperConfigModel->validity_period->start_time) {
                throw new AuthorizationException('考试还未开始');
            }
            else if ($time > $paperConfigModel->validity_period->end_time) {
                throw new AuthorizationException('考试已结束');
            }
        }
    }

    public function checkAnswerFrequency(UserAnswerModel $userAnswerModel, PaperConfigModel $paperConfigModel)
    {
        if ($paperConfigModel->answer_frequency > 0) {
            if ($userAnswerModel && $userAnswerModel->answer_frequency >= $paperConfigModel->answer_frequency) {
                throw new AuthorizationException('已达到最大答题次数');
            }
        }
    }

    public function check(int $user_id, PaperModel $paperModel)
    {
        $this->checkPaperStatus($paperModel);
        $paperApplyModel = $this->paperApplyService->getApplyUser($paperModel->id, $user_id);
        if (!$paperApplyModel) throw new AuthorizationException('出题者已开启访问限制，需要申请审批后才能进入');
        $paperConfigModel = $this->paperConfigService->getByPaperId($paperModel->id);
        $this->checkVisitStatus($paperApplyModel, $paperConfigModel);
        $this->checkPaperValidityPeriod($paperConfigModel);
        $userAnswerModel = $this->getUserAnswer($user_id, $paperModel->id);
        if ($userAnswerModel) {
            $this->checkAnswerFrequency($userAnswerModel, $paperConfigModel);
        }
    }

    public function getAnswerPaper(int $paper_id)
    {
        $answerPaperTemp = \Storage::disk('paper')->get($paper_id . '.json');
        $answerPaperTemp = json_decode($answerPaperTemp);

        if ($answerPaperTemp->mode == PaperEnum::MODE_BRUSH) {
            $answerPaperTemp->topic = [];
            $userAnswerModel        = $this->getUserAnswer(\UserService::getCurrentUserId(), $paper_id);
            $answerTopics           = $userAnswerModel->content->answer_topic;
            $paperTopicModels       = PaperTopicModel::query()
                ->join('topic', 'topic.id', '=', 'paper_topic.topic_id')
                ->wherePaperId($paper_id)
                ->whereNotIn('topic_id', $answerTopics)
                ->inRandomOrder()
                ->limit(15)
                ->orderBy('topic.topic_type_id')
                ->selectRaw('topic.*, paper_topic.score, paper_topic.weight')
                ->get();

            foreach ($paperTopicModels as $topic) {
                unset($topic->answer, $topic->answer_analysis, $topic->created_by, $topic->created_at, $topic->updated_at);
                foreach ($topic->option as &$option) {
                    unset($option->answer);
                }
                $answerPaperTemp->topic[] = $topic;
            }
        }

        return $answerPaperTemp;
    }

    public function create(int $paper_id, int $user_id, array $data)
    {
        $userAnswerModel = $this->getUserAnswer($user_id, $paper_id);
        if ($userAnswerModel) throw new InternalErrorException('答题记录已存在');

        $userAnswerModel                   = new UserAnswerModel();
        $userAnswerModel->user_id          = $user_id;
        $userAnswerModel->paper_id         = $paper_id;
        $userAnswerModel->answer_frequency = $data['answer_frequency'] ?? 0;
        $userAnswerModel->content          = $this->setContent($paper_id, $data);
        $userAnswerModel->save();

        return $userAnswerModel;
    }

    public function setContent(int $paper_id, array $data)
    {
        $answerPaperTemp = \Storage::disk('paper')->get($paper_id . '.json');
        $answerPaperTemp = json_decode($answerPaperTemp, true);

        $dataArchives  = array_column($data['archives'] ?? [], null, 'id');
        $paperArchives = $answerPaperTemp['archives'];
        foreach ($paperArchives as &$item) {
            if (isset($dataArchives[$item['id']])) $item['value'] = $dataArchives[$item['id']]['value'];
        }

        $dataTopics  = array_column($data['topic'] ?? [], null, 'id');
        $paperTopics = $answerPaperTemp['topic'];
        foreach ($paperTopics as &$item) {
            if (isset($dataTopics[$item['id']])) $item['user_answer'] = array_map('strtoupper', $dataTopics[$item['id']]['answer']);
        }

        $insertContent = [
            'paper_title'       => $answerPaperTemp['title'],
            'archives'          => $paperArchives,
            'topic'             => $paperTopics,
            'paper_total_score' => $answerPaperTemp['mode'] == PaperEnum::MODE_BRUSH ? 0 : $answerPaperTemp['total_score'],
            'topic_number'      => $answerPaperTemp['topic_number'],
            'limited_time'      => $answerPaperTemp['limited_time'],
            'start_answer_time' => $data['start_answer_time'] ?? 0,
            'submit_paper_time' => 0,
        ];

        return $insertContent;
    }

    public function update(int $paper_id, int $user_id, array $data)
    {
        $userAnswerModel = $this->getUserAnswer($user_id, $paper_id);
        if (!$userAnswerModel) throw new NotFoundHttpException('答题记录不存在');

        $answerContent = $userAnswerModel->content;
        $dataArchives  = array_column($data['archives'], null, 'id');
        foreach ($answerContent->archives as &$item) {
            if (isset($dataArchives[$item->id])) $item->value = $dataArchives[$item->id]['value'];
        }

        $dataTopics = array_column($data['topic'], null, 'id');
        foreach ($answerContent->topic as &$item) {
            if (isset($dataTopics[$item->id])) $item->user_answer = array_map('strtoupper', $dataTopics[$item->id]['answer']);
        }

        $userAnswerModel->content = $answerContent;
        $userAnswerModel->update();

        return $userAnswerModel;
    }

    public function startAnswer(int $user_id, int $paper_id)
    {
        $paperModel = PaperModel::query()->find($paper_id);
        if (!$paperModel) throw new NotFoundHttpException('考卷不存在或已被删除');
        $this->check($user_id, $paperModel);

        $userAnswerModel = $this->getUserAnswer($user_id, $paper_id);
        if (!$userAnswerModel) {
            $userAnswerModel = $this->create($paper_id, $user_id, [
                'start_answer_time' => time(),
                'answer_frequency'  => 1,
            ]);
        }
        else {
            $answerContent                     = $userAnswerModel->content;
            $answerContent->start_answer_time  = $userAnswerModel->content->start_answer_time ? $userAnswerModel->content->start_answer_time : time();
            $userAnswerModel->content          = $answerContent;
            $userAnswerModel->answer_frequency += 1;
            $userAnswerModel->update();
        }

        return $userAnswerModel;
    }

    public function submitAnswerPaper(int $user_id, int $paper_id)
    {
        $submitPaperTime = time();
        $paperModel      = PaperModel::query()->find($paper_id);
        if (!$paperModel) throw new NotFoundHttpException('考卷不存在或已被删除');
        $this->check($user_id, $paperModel);

        $userAnswerModel = UserAnswerModel::query()->whereUserId($user_id)->wherePaperId($paper_id)->first();
        if (!$userAnswerModel || empty($userAnswerModel->content->start_answer_time)) throw new AuthorizationException('非法请求');
        if (($submitPaperTime - $userAnswerModel->content->start_answer_time) < 60) throw new AuthorizationException('开始答题之后需要等待1分钟之后才能交卷');

        $answerPaperTemp = \Storage::disk('paper')->get($paper_id . '.json');
        $answerPaperTemp = json_decode($answerPaperTemp);

        $topicNumber        = 0;
        $userTotalScore     = 0;
        $correctTopicNumber = 0;
        $errorTopicNumber   = 0;
        $blankTopicNumber   = 0;
        $answerContent      = $userAnswerModel->content;
        foreach ($answerContent->topic as $topic) {
            $topicNumber += 1;
            if (empty($topic->user_answer)) {
                $topic->user_score         = 0;
                $blankTopicNumber          += 1;
                $topic->user_answer_status = UserAnswerEnum::ANSWER_STATUS_BLANK;
            }
            else {
                // 如果题目类型是填空题，并且组卷方式是随机抽题，则需要判断漏选得分
                if ($topic->topic_type_id == TopicTypeEnum::BLANK) {
                    foreach ($topic->answer as $key => $answer) {
                        if (isset($topic->user_answer[$key]) && array_diff($topic->user_answer[$key], $answer)) {
                            $topic->user_score         = 0;
                            $errorTopicNumber          += 1;
                            $topic->user_answer_status = UserAnswerEnum::ANSWER_STATUS_ERR;
                        }
                        else {
                            $correctTopicNumber        += 1;
                            $topic->user_answer_status = UserAnswerEnum::ANSWER_STATUS_OK;
                            $user_answer               = strtolower(implode(',', $topic->user_answer[$key] ?? []));
                            $topic_answer              = strtolower(implode(',', $answer));
                            if ($user_answer == $topic_answer) {
                                $topic->user_score = $topic->score;
                                $userTotalScore    += $topic->user_score;
                            }
                            else {
                                if ($answerPaperTemp->organization_method->type == PaperEnum::ORGANIZATION_METHOD_RAND) {
                                    $randomConfig      = $answerPaperTemp->organization_method->random->config;
                                    $randomConfig      = array_column($randomConfig, null, 'topic_type_id');
                                    $topic->user_score = $randomConfig[$topic->topic_type_id]['missing_score'] ?? 0;
                                }
                                else {
                                    $topic->user_score = $topic->score;
                                }
                                $userTotalScore += $topic->user_score;
                            }
                        }
                    }
                }
                else {
                    $topic_answer = [];
                    foreach ($topic->option as $option) {
                        if ($option->answer) $topic_answer[] = $option->name;
                    }

                    $user_answer  = strtolower(implode(',', $topic->user_answer));
                    $topic_answer = strtolower(implode(',', $topic_answer));
                    if ($user_answer == $topic_answer) {
                        $topic->user_score         = $topic->score;
                        $userTotalScore            += $topic->user_score;
                        $correctTopicNumber        += 1;
                        $topic->user_answer_status = UserAnswerEnum::ANSWER_STATUS_OK;
                    }
                    else {
                        $topic->user_score         = 0;
                        $errorTopicNumber          += 1;
                        $topic->user_answer_status = UserAnswerEnum::ANSWER_STATUS_ERR;
                    }
                }
            }
        }

        $answerContent->paper_mode           = $answerPaperTemp->mode;
        $answerContent->submit_paper_time    = $submitPaperTime;                                                    // 交卷时间
        $answerContent->correct_rate         = ($correctTopicNumber / $topicNumber) * 100;                          // 正确率
        $answerContent->error_rate           = ($errorTopicNumber / $topicNumber) * 100;                            // 错误率
        $answerContent->complete_rate        = (($correctTopicNumber + $errorTopicNumber) / $topicNumber) * 100;    // 完成率
        $answerContent->correct_topic_number = $correctTopicNumber;                                                 // 答对题目数量
        $answerContent->error_topic_number   = $errorTopicNumber;                                                   // 错题数量
        $answerContent->blankTopicNumber     = $blankTopicNumber;                                                   // 空题数量
        $answerContent->answer_time          = $submitPaperTime - $answerContent->start_answer_time;                // 答题时长
        $answerContent->user_total_score     = $userTotalScore;                                                     // 用户总得分

        $userAnswerHistory           = new UserAnswerHistoryModel();
        $userAnswerHistory->user_id  = $user_id;
        $userAnswerHistory->paper_id = $paper_id;
        $userAnswerHistory->content  = $answerContent;

        \DB::beginTransaction();
        try {
            $userAnswerHistory->save();
            $userAnswerModel->content = $this->setContent($paper_id, []);
            $userAnswerModel->update();
            \DB::commit();
        } catch (\Exception $exception) {
            \DB::rollBack();
            throw $exception;
        }

        return $userAnswerHistory;
    }
}