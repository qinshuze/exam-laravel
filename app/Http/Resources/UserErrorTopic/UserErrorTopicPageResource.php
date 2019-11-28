<?php


namespace App\Http\Resources\UserErrorTopic;


use Illuminate\Http\Resources\Json\JsonResource;

class UserErrorTopicPageResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'                 => $this->id,
            'paper'              => [
                'id'              => $this->paper->id,
                'title'           => $this->paper->title,
                'front_cover_url' => $this->paper->front_cover,
                'total_score'     => $this->content->paper_total_score,
            ],
            'error_topic_number' => $this->content->error_topic_number,
        ];
    }
}