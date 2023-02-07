<?php

namespace App\Http\Resources;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

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
            'created_at' => Carbon::parse($this->created_at)->format('Y-m-d'),
            'updated_at' => Carbon::parse($this->updated_at)->format('Y-m-d'),
        ];
    }
}
