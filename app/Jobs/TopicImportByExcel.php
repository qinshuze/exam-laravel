<?php

namespace App\Jobs;

use App\Imports\TopicImport;
use App\Models\PaperModel;
use App\Models\PaperTopicModel;
use App\Models\TopicModel;
use App\Models\TopicTypeModel;
use App\Models\UploadFileModel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class TopicImportByExcel implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $paperId;
    private $fileId;

    /**
     * Create a new job instance.
     *
     * @param $paperId
     * @param $fileId
     */
    public function __construct($paperId, $fileId)
    {
        $this->paperId = $paperId;
        $this->fileId = $fileId;
    }

    public function handle()
    {
        $this->importTopic();
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws \Throwable
     */
    private function importTopic()
    {
        $uploadFileModel = UploadFileModel::query()->find($this->fileId);
        $storagePath = \Storage::path('excel');

        // 获取表格数据
        $data = \Excel::toArray(new TopicImport(), $storagePath . '/' . $uploadFileModel->path);
        $thead = array_shift($data[0]);
        $rows = $data[0];

        // 获取题目类型列表
        $topicTypeList = TopicTypeModel::query()->get()->keyBy('name')->toArray();
        // 获取答案在表格列的位置
        $answerIndex = array_search('正确答案', $thead);

        $userId = \UserService::getCurrentUserId();
        $datetime = date('Y-m-d H:i:s');
        $topicList = [];
        for ($i = 0; $i < count($rows); $i++) {
            if (!$rows[$i][0]) continue;

            $option = $this->getTopicOption($rows[$i], 2, $answerIndex, $thead);
            $answer = $option ? [] : explode('|', $rows[$i][7]) ?? [] ;
            $topicList[] = [
                'topic_type_id' => $topicTypeList[$rows[$i][0]]['id'],
                'content' => $rows[$i][1],
                'media' => '[]',
                'option' => json_encode($option),
                'answer' => json_encode($answer),
                'answer_analysis' => $item[8] ?? '',
                'created_by' => $userId,
                'created_at' => $datetime,
                'updated_at' => $datetime,
            ];

            if (count($topicList) > 500) {
                $this->insertDb($topicList);
                $topicList = [];
            }
        }

        if (count($topicList)) {
            $this->insertDb($topicList);
        }
    }

    private function getTopicOption($row, $startIndex, $endIndex, $thead) {
        $option = [];
        preg_match_all('/[A-Z]/', $row[7], $optionAnswer);
        $optionAnswer = array_change_key_case(array_flip($optionAnswer[0]), CASE_UPPER);
        for ($i = $startIndex; $i < $endIndex; $i++) {
            if (!$row[$i]) continue;
            preg_match('/[A-Z]/', $thead[$i], $name);
            $name = strtoupper($name[0]);
            $option[] = [
                'name' => $name,
                'value' => $row[$i],
                'media' => [],
                'answer' => isset($optionAnswer[$name])
            ];
        }

        return $option;
    }

    private function insertDb($topicList)
    {
        $topicLastId = TopicModel::max('id');

        $n = count($topicList);
        $paperTopicList = [];
        $currentTopicId = 0;
        $weight = PaperTopicModel::query()->where('paper_id', $this->paperId)->max('weight') ?? 0;
        for ($i = 0; $i < $n; $i++) {
            $currentTopicId++;
            $currentTopicId += $topicLastId;
            $topicList[$i]['id'] = $currentTopicId;
            $paperTopicList[] = [
                'paper_id' => $this->paperId,
                'topic_id' => $currentTopicId,
                'score' => 0,
                'weight' => ++$weight,
            ];
        }

        \DB::beginTransaction();
        try {
            TopicModel::query()->insert($topicList);
            PaperTopicModel::query()->insert($paperTopicList);
            PaperModel::query()->whereKey($this->paperId)->increment('topic_number', $n);
            \DB::commit();
        } catch (\Exception $exception) {
            \DB::rollBack();
            throw $exception;
        }
    }
}