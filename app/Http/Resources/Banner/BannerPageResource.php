<?php


namespace App\Http\Resources\Banner;


use Illuminate\Http\Resources\Json\JsonResource;

class BannerPageResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'     => $this->id,
            'path'   => $this->path,
            'weight' => $this->weight,
            'width'  => $this->width,
            'height' => $this->height,
        ];
    }
}