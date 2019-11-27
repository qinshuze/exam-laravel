<?php


namespace App\Http\Resources\PaperTopic;


use Illuminate\Http\Resources\Json\JsonResource;

class PaperTopicTypeQuantityResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'topic_type_id' => $this->resource->topic_type_id,
            'count'         => $this->resource->count,
            'amount'        => $this->resource->amount ?? 0,
            'score'         => $this->resource->score ?? 0,
            'missing_score' => $this->resource->missing_score ?? 0,
        ];
    }
}