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
            'tags' => $this->tags ? $this->tags->map(function ($tag) {
                return $tag->name;
            }) : [],
            'more_images' => $this->images,
            'privacy' => $this->privacy,
            'access_list' => $this->access_list,
        ];
    }
}
