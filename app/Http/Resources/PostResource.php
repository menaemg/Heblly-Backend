<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Termwind\Components\Dd;

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
        if ($this->user) {
            return [
                'id' => $this->id,
                'slug' => $this->slug,
                'title' => $this->title,
                'body' => $this->body,
                'location' => $this->location,
                'privacy' => $this->privacy,
                'main_image' => $this->main_image,
                'has-liked' => $this->hasLiked,
                'user' => [
                    'id' => $this->user->id,
                    'username' => $this->user->username,
                    'name' => $this->user->profile ? $this->user->profile->full_name : $this->user->username,
                    'avatar' => $this->user->profile ? $this->user->profile->avatar_url : null,
                    'bio' => $this->user->profile ? $this->user->profile->bio : null,
                    'location' => $this->user->profile ? $this->user->profile->location : null,
                ],
                'images' => $this->images,
                'tags' => $this->tags,
                'isWishList' => $this->is_wish_list,
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at,
            ];
        }
    }
}
