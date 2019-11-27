<?php


namespace App\Http\Resources\Paper;


use Illuminate\Http\Resources\Json\JsonResource;

class PaperListResource extends JsonResource
{
    public function toArray($request)
    {
        $front_cover = $this->front_cover ? $this->front_cover : config('system.paper.default_front_cover');
        return [
            'id'              => $this->id,
            'title'           => $this->title,
            'front_cover_url' => \UploadFileService::pathToUrl($front_cover),
            'paper_type_id'   => $this->paper_type_id,
            'answer_number'   => $this->answer_number,
            'status'          => $this->status,
            'topic_number'    => $this->topic_number,
        ];
    }
}