<?php


namespace App\Http\Resources\Paper;


use Illuminate\Http\Resources\Json\JsonResource;

class PaperCreateResource extends JsonResource
{
    public function toArray($request)
    {
        $default_front_cover = '20191105/05a82d4d656e281c76dbc27e6e7f63c3.png';
        return [
            'id'              => $this->id,
            'title'           => $this->title,
            'front_cover_url' => \UploadFileService::pathToUrl($this->front_cover??$default_front_cover),
            'paper_type_id'   => $this->paper_type_id,
            'answer_number'   => $this->answer_number,
            'status'          => $this->status,
            'topic_number'    => $this->topic_number,
        ];
    }
}