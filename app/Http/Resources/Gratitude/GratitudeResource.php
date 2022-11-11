<?php

namespace App\Http\Resources\Gratitude;

use Illuminate\Http\Resources\Json\JsonResource;

class GratitudeResource extends JsonResource
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
            'from' => $this->from_friend,
            'location' => $this->location,
            'main_image' => $this->main_image,
            'tags' => $this->tags->map(function ($tag) {
                return $tag->name;
            }),
            'more_images' => $this->images,
        ];
    }
}
