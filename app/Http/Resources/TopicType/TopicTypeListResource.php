<?php


namespace App\Http\Resources\TopicType;


use Illuminate\Http\Resources\Json\JsonResource;

class TopicTypeListResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }
}