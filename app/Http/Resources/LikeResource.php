<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LikeResource extends JsonResource
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
            'id' => $this->id,
            'username' => $this->username,
            'name' => $this->profile ? $this->profile->name : null,
            'avatar' => $this->profile ? $this->profile->avatar_url : null,
            'cover' => $this->profile ? $this->profile->cover_url : null,
            'followers_count' => $this->followers()->count(),
            'is_private' => $this->profile && $this->profile->privacy == 'private' ? true :false,
        ];
    }
}
