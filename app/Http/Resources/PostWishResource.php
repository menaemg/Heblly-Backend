<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PostWishResource extends JsonResource
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
            'title' => $this->title,
            'body' => $this->body,
            'location' => $this->location,
            'main_image' => $this->main_image,
            'user' => [
                'id' => $this->user->id,
                'username' => $this->user->username,
                'name' => $this->user->profile ? $this->user->profile->full_name : $this->user->username,
                'avatar' => $this->user->profile ? $this->user->profile->avatar_url : null,
                'bio' => $this->user->profile ? $this->user->profile->bio : null,
                'location' => $this->user->profile ? $this->user->profile->location : null,
            ],
            'tags' => $this->tags ? $this->tags->map(function ($tag) {
                return $tag->name;
            }) : [],
            'more_images' => $this->images,
            'privacy' => $this->privacy,
            'access_list' => $this->access_list,
            'created_at'  => $this->created_at,
            'updated_at'  => $this->updated_at
        ];
    }
}
