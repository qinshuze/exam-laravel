<?php


namespace App\Http\Resources\UserApply;


use Illuminate\Http\Resources\Json\JsonResource;

class UserApplyDetailResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'          => $this->id,
            'user_id'     => $this->user_id,
            'username'    => $this->username,
            'wechat'      => $this->wechat,
            'status'      => $this->status,
            'description' => $this->description,
        ];
    }
}