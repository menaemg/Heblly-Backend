<?php

namespace App\Auth;

use Illuminate\Support\Str;
use Illuminate\Auth\GenericUser;
use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Auth\UserProvider;

class AdminUserProvider extends EloquentUserProvider
{
    public function retrieveByCredentials(array $credentials)
    {
        // dd('yes');
        if (empty($credentials) ||
            (count($credentials) === 1 &&
                array_key_exists('password', $credentials))) {
            return;
        }

        $query = $this->createModel()->newQuery();

        foreach ($credentials as $key => $value) {
            if (! Str::contains($key, 'password')) {
                $query->where($key, $value);
            }
        }

        // Add condition to check user type
        $query->where('type', 'admin');

        return $query->first();
    }
}
