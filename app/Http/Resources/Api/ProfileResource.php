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
            'id' => $this->id ?? null,
            'name' => $this->profile->full_name ?? null,
            'username' => $this->username ?? null,
            'email' => $this->email ?? null,
            'followings_count' => $this->followings_count ?? null,
            'followers_count' => $this->followables_count ?? null,
            'bio' => $this->profile->bio ?? null,
            'phone' => $this->profile->phone ?? null,
            'avatar' => $this->profile->avatar_url ?? null,
            'cover' => $this->profile->cover_url ?? null,
            'address' => $this->profile->address ?? null,
            'city' => $this->profile->city ?? null,
            'state' => $this->profile->state ?? null,
            'country' => $this->profile->country ?? null,
            'zip' => $this->profile->zip ?? null,
            'local' => $this->profile->local ?? null,
            'privacy' => $this->profile->privacy ?? null,
        ];
    }
}
