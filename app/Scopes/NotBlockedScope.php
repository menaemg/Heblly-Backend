<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class NotBlockedScope implements Scope
{
    /* @param  \Illuminate\Database\Eloquent\Builder  $builder
    * @param  \Illuminate\Database\Eloquent\Model  $model
    * @return void
    */
    public function apply(Builder $builder, Model $model)
    {
        // dump(Auth()->user()->id);
        // dd($model->find(Auth()->id())->blocklist()->get());
        // $blockedIds = $model->blocklist()->get()->toArray();
        // dd($blockedIds);
        // $builder->where('created_at', '<', now()->subYears(2000));
    }
}