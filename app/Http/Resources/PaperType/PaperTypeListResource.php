<?php


namespace App\Http\Resources\PaperType;


use Illuminate\Http\Resources\Json\JsonResource;

class PaperTypeListResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }
}