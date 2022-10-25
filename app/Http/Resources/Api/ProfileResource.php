<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
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
            'name' => $this->full_name,
            'username' => $this->username,
            'email' => $this->email,
            'followings_count' => $this->followings_count,
            'followers_count' => $this->followables_count,
            'bio' => $this->profile->bio,
            'phone' => $this->profile->phone,
            'avatar' => $this->profile->avatar_url,
            'cover' => $this->profile->cover_url,
            'address' => $this->profile->address,
            'city' => $this->profile->city,
            'state' => $this->profile->state,
            'country' => $this->profile->country,
            'zip' => $this->profile->zip,
            'local' => $this->profile->local,
            'privacy' => $this->profile->privacy,
        ];
    }
}
