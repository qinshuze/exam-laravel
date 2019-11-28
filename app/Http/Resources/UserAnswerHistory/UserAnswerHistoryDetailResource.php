<?php


namespace App\Http\Resources\UserAnswerHistory;


use Illuminate\Http\Resources\Json\JsonResource;

class UserAnswerHistoryDetailResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'    => $this->id,
            'paper' => [
                'id'              => $this->paper->id,
                'title'           => $this->paper->title,
                'mode'            => $this->content->paper_mode,
                'front_cover_url' => $this->paper->front_cover,
                'total_score'     => $this->content->paper_total_score,
                'topic_number'    => $this->content->topic_number,
                'topic'           => $this->content->topic,
                'archives'        => $this->content->archives,
                'custom_archives' => $this->content->custom_archives,
            ],
        ];
    }
}