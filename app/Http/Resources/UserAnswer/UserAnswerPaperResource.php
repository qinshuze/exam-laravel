<?php


namespace App\Http\Resources\UserAnswer;


use Illuminate\Http\Resources\Json\JsonResource;

class UserAnswerPaperResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'                     => $this->id,
            'archives'               => $this->archives,
            'topic_type_description' => $this->topic_type_description,
            'topic'                  => $this->topic
        ];
    }
}