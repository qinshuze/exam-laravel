<?php


namespace App\Services;


use App\Enums\ExceptionMessageEnum;
use App\Enums\TopicTypeEnum;
use App\Models\TopicModel;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TopicService
{
    /**
     * @param int $id
     * @return TopicModel|TopicModel[]|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     */
    public function getById(int $id)
    {
        return TopicModel::query()->find($id);
    }

    public function create(array $data)
    {
        $option = [];
        if (isset($data['option'])) {
            foreach ($data['option'] as &$item) {
                $item['name'] = strtoupper($item['name']);
            }
            $option = $data['option'];
        }

        $model                  = new TopicModel();
        $model->content         = $data['content'] ?? '';
        $model->topic_type_id   = $data['topic_type_id'];
        $model->media           = $data['media'] ?? [];
        $model->option          = $option;
        $model->answer          = $data['answer'] ?? [];
        $model->answer_analysis = $data['answer_analysis'] ?? '';
        $model->created_by      = \UserService::getCurrentUserId();
        $model->save();

        return $model;
    }

    public function update(int $id, array $data)
    {
        $model = $this->getById($id);
        if (!$model) throw new NotFoundHttpException(ExceptionMessageEnum::TOPIC_NOT_NULL);

        if (isset($data['content'])) $model->content = $data['content'];
        if (isset($data['topic_type_id'])) $model->topic_type_id = $data['topic_type_id'];
        if (isset($data['media'])) $model->media = $data['media'];
        if (isset($data['option'])) {
            foreach ($data['option'] as &$item) {
                $item['name'] = strtoupper($item['name']);
            }
            $model->option = $data['option'];
        }
        if (isset($data['answer'])) $model->answer = $data['answer'];
        if (isset($data['answer_analysis'])) $model->answer_analysis = $data['answer_analysis'];
        $model->update();

        return $model;
    }

    public function deleteBatch(array $ids)
    {
        return TopicModel::query()->whereIn('id', $ids)->delete();
    }

    public function checkIntegrity(array $topics)
    {
        $errTopics = [];
        foreach ($topics as $topic) {
            if (empty($topic['content'])) {
                $errTopics[$topic['id']]['message'][] = '缺少题目内容';
                $errTopics[$topic['id']]['topic'] = $topic;
            }

            if (!isset($topic['score'])) {
                $errTopics[$topic['id']]['message'][] = '缺少题目分数';
                $errTopics[$topic['id']]['topic'] = $topic;
            }

            if ($topic['topic_type_id'] !== TopicTypeEnum::BLANK) {
                if (empty($topic['option'])) {
                    $errTopics[$topic['id']]['message'][] = '题目类型为选项题时，必须提供一个或多个选项';
                    $errTopics[$topic['id']]['topic'] = $topic;
                } else {
                    $answer = [];
                    foreach ($topic['option'] as $option) {
                        if (isset($option['answer']) && $option['answer']) $answer[] = $option['answer'];
                    }
                    if (!count($answer)) {
                        $errTopics[$topic['id']]['message'][] = '缺少答案选项';
                        $errTopics[$topic['id']]['topic'] = $topic;
                    } elseif ($topic['topic_type_id'] === TopicTypeEnum::RADIO && count($answer) > 1) {
                        $errTopics[$topic['id']]['message'][] = '题目类型为单选题时，不允许出现多个答案选项';
                        $errTopics[$topic['id']]['topic'] = $topic;
                    }
                }
            } else {
                if($topic['topic_type_id'] === TopicTypeEnum::BLANK && empty($topic['answer'])) {
                    $errTopics[$topic['id']]['message'][] = '缺少题目答案';
                    $errTopics[$topic['id']]['topic'] = $topic;
                }
            }
        }

        return $errTopics;
    }
}