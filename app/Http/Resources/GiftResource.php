<?php

namespace App\Http\Resources;

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
                'slug' => $this->slug,
                'title' => $this->title,
                'body' => $this->body,
                'gift_for' => $this->gift_for_user,
                'location' => $this->location,
                'privacy' => $this->privacy,
                'main_image' => $this->main_image,
                'user' => $this->user,
                'images' => $this->images,
                'tags' => $this->tags,
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at,
                'post' => $this->post ?? null,
        ];

    }
}
