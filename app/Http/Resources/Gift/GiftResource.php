<?php

namespace App\Http\Resources\Gift;

use Illuminate\Http\Resources\Json\JsonResource;
use Termwind\Components\Dd;

class GiftResource extends JsonResource
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
            'for' => $this->for_friend,
            'body' => $this->body,
            'user' => [
                'id' => $this->user->id,
                'username' => $this->user->username,
                'name' => $this->user->profile ? $this->user->profile->full_name : $this->user->username,
                'avatar' => $this->user->profile ? $this->user->profile->avatar_url : null,
                'cover' => $this->user->profile ? $this->user->profile->cover_url : null,
                'followers_count' => $this->user->followers()->count(),
                'is_private' => $this->user->profile && $this->user->profile->privacy == 'private' ? true :false,
                'bio' => $this->user->profile ? $this->user->profile->bio : null,
                'location' => $this->user->profile ? $this->user->profile->location : null,
            ],
            'from' => $this->from_friend ? [
                'id' => $this->from_friend->id,
                'name' => $this->from_friend->name,
                'avatar' => $this->from_friend->profile ? $this->from_friend->profile->avatar_url : null,
            ] : null,
            'location' => $this->location,
            'main_image' => $this->main_image,
            'tags' => $this->tags ? $this->tags->map(function ($tag) {
                return $tag->name;
            }) : [],
            'more_images' => $this->images,
            'privacy' => $this->privacy,
            'access_list' => $this->access_list,
            'created_at'  => $this->created_at,
        ];
    }
}
