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
        if (auth('sanctum')->check()) {
            // \dd($this->authUser->blockIds->toArray());

            $builder->whereNotIn('users.id', $this->authUser->blockIds->toArray());
        }
        // dump(Auth()->user()->id);
        // dd($model->find(Auth()->id())->blocklist()->get());
        // $blockedIds = $model->blocklist()->get()->toArray();
        // dd($blockedIds);
        // $builder->where('created_at', '<', now()->subYears(2000));
    }
}
