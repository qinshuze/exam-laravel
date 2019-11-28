<?php


namespace App\Http\Resources\UserErrorTopic;


use Illuminate\Http\Resources\Json\JsonResource;

class UserErrorTopicDetailResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'    => $this->id,
            'paper' => [
                'id'              => $this->paper->id,
                'title'           => $this->paper->title,
                'front_cover_url' => $this->paper->front_cover,
                'total_score'     => $this->content->paper_total_score,
            ],
        ];
    }
}