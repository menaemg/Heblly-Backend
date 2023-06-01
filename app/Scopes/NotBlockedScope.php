<?php

namespace App\Scopes;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Builder;
use Termwind\Components\Dd;

class NotBlockedScope implements Scope
{
    public $authUser;


    public function __construct($authUser)
    {
        $this->authUser = $authUser;
    }

    /* @param  \Illuminate\Database\Eloquent\Builder  $builder
    * @param  \Illuminate\Database\Eloquent\Model  $model
    * @return void
    */
    public function apply(Builder $builder, Model $model)
    {
        // dd('yes');
        // dd(auth('sanctum')->user());
        if ($this->authUser) {
            if ($this->authUser->type != "admin") {
                $builder->whereNotIn('users.id', optional($this->authUser->blockIds)->toArray());
            }
        }

    }
}
