<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BlockResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->blockUser->id,
            'username' => $this->blockUser->username,
            'name' => $this->blockUser->profile ? $this->blockUser->profile->name : null,
            'avatar' => $this->blockUser->profile ? $this->blockUser->profile->avatar_url : null,
            'type' => $this->type
        ];
    }
}
