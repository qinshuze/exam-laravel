<?php


namespace App\Http\Resources\PaperTopic;


use Illuminate\Http\Resources\Json\JsonResource;

class PaperTopicSaveResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'paper_id'        => $this->paper_id,
            'id'              => $this->id,
            'content'         => $this->content,
            'weight'          => $this->weight,
            'score'           => $this->score,
            'topic_type_id'   => $this->topic_type_id,
            'media'           => $this->media,
            'option'          => $this->option,
            'answer'          => $this->answer,
            'answer_analysis' => $this->answer_analysis,
        ];
    }
}