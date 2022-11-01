<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

    class PostResource extends JsonResource
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
            'slug' => $this->slug,
            'title' => $this->title,
            'body' => $this->body,
            'location' => $this->location,
            'privacy' => $this->privacy,
            'main_image' => $this->main_image,
            'user' => [
                'id' => $this->user->id,
                'username' => $this->user->username,
                'name' => $this->user->profile->full_name ?? $this->user->username,
                'avatar' => $this->user->profile->avatar_url ?? null,
                'bio' => $this->user->profile->bio ?? null,
                'location' => $this->user->profile->location ?? null,
                'email' => $this->user->email,
            ],
            'images' => $this->image,
            'tags' => $this->tags,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
