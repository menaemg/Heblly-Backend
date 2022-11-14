<?php

namespace App\Http\Resources\Gift;

use Illuminate\Http\Resources\Json\JsonResource;

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
            'from' => $this->from_friend,
            'location' => $this->location,
            'main_image' => $this->main_image,
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
