<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class CommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $avatar = User::find($this->commentator->id)->profile ? User::find($this->commentator->id)->profile->avatar_url : null;
        return [
            'id' => $this->id,
            'comment' => $this->comment,
            'comment_by' => [
                'id' => $this->commentator->id,
                'username' => $this->commentator->username,
                'avatar' => $avatar,
            ],
            'commentator_profile' => $this->commentator->profile,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
