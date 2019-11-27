<?php


namespace App\Http\Resources\UploadFile;


use Illuminate\Http\Resources\Json\JsonResource;

class UploadFileResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'url' => \UploadFileService::pathToUrl($this->path),
        ];
    }
}