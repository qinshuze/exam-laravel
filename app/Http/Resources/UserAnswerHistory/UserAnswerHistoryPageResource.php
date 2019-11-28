<?php


namespace App\Http\Resources\UserAnswerHistory;


use Illuminate\Http\Resources\Json\JsonResource;

class UserAnswerHistoryPageResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'                   => $this->id,
            'paper'                => [
                'id'              => $this->paper_id,
                'title'           => $this->paper->title,
                'front_cover_url' => \UploadFileService::pathToUrl($this->paper->front_cover),
                'mode'            => $this->content->paper_mode,
                'total_score'     => $this->content->paper_total_score,
                'topic_number'    => $this->content->topic_number,
            ],
            'user_total_score'     => $this->content->user_total_score,
            'start_answer_time'    => $this->content->start_answer_time,
            'correct_topic_number' => $this->content->correct_topic_number,
            'complete_rate'        => $this->content->complete_rate,
        ];
    }
}