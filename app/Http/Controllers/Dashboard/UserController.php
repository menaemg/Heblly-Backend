<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\User;
use Inertia\Inertia;
use App\Models\Organization;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Redirect;

class UserController extends Controller
{
    public function index()
    {
        return Inertia::render('Users/Index', [
            'filters' => Request::all('search', 'trashed'),
            'users' => User
                // ->withQueryString()
                ::with('profile')
                ->filter(Request::only('search', 'trashed'))
                ->withCount(['followings', 'followables', 'posts'])
                ->paginate(10)
                ->withQueryString()
                ->through(fn ($user) => [
                    'id' => $user->id,
                    'name' => $user->username,
                    'email' => $user->email,
                    'avatar' =>  $user->profile ? $user->profile->avatar_url : null,
                    'posts_count' => $user->posts_count ?? 0,
                    'followings_count' => $user->followings_count ?? 0,
                    'followers_count' => $user->followables_count ?? 0,
                    // 'deleted_at' => $organization->deleted_at,
                ]),
        ]);
    }

    public function create()
    {
        return Inertia::render('Users/Create');
    }

    public function store()
    {
        Auth::user()->account->organizations()->create(
            Request::validate([
                'name' => ['required', 'max:100'],
                'email' => ['nullable', 'max:50', 'email'],
                'phone' => ['nullable', 'max:50'],
                'address' => ['nullable', 'max:150'],
                'city' => ['nullable', 'max:50'],
                'region' => ['nullable', 'max:50'],
                'country' => ['nullable', 'max:2'],
                'postal_code' => ['nullable', 'max:25'],
            ])
        );

        return Redirect::route('users')->with('success', 'Organization created.');
    }

    public function edit(User $user)
    {
        return Inertia::render('Users/Edit', [
            'user' => [
                'id' => $user->id ?? null,
                // 'name' => $user->profile->name ?? null,
                'username' => $user->username ?? null,
                'email' => $user->email ?? null,
                // 'followings_count' => $user->followings_count ?? null,
                // 'followers_count' => $user->followables_count ?? null,
                'bio' => $user->profile->bio ?? null,
                'website' => $user->profile->website ?? null,
                'birthday' => $user->profile->birthday ?? null,
                'phone' => $user->profile->phone ?? null,
                'gender' => $user->profile->gender ?? null,
                'avatar' => $user->profile->avatar_url ?? null,
                'cover' => $user->profile->cover_url ?? null,
                'address' => $user->profile->address ?? null,
                'city' => $user->profile->city ?? null,
                'state' => $user->profile->state ?? null,
                'country' => $user->profile->country ?? null,
                'zip' => $user->profile->zip ?? null,
                'local' => $user->profile->local ?? null,
                'privacy' => $user->profile->privacy ?? null,
            ],
        ]);
    }

    public function update(User $user)
    {
        $user->update(
            array_filter(Request::validate([
                'username' => 'string|alpha_dash|max:255|unique:users,username,' . $user->username . ',username',
                'email' => 'string|email|max:255|unique:users,email,' . $user->email . ',email',
                'name' => 'string|max:255',
                'bio' => 'string|max:255',
                'gender' => 'boolean',
                'phone' => 'string|max:255',
                'website' => 'string|max:255',
                'birthday' => 'date',
                'avatar' => 'image|max:2000',
                'cover' => 'image|max:2000',
                'address' => 'string|max:255',
                'city' => 'string|max:255',
                'state' => 'string|max:255',
                'country' => 'string|max:3',
                'zip' => 'string|max:255',
                'local' => 'string|max:3',
                'privacy' => 'in:public,private',
                'password' => 'nullable|string|max:255|min:4',
            ])
        ));

        return Redirect::back()->with('success', 'User updated.');
    }

    public function destroy(User $user)
    {

        $user->posts()->delete();

        $user->load('posts');

        $user->delete();

        return Redirect::route('users')->with('success', 'User deleted.');
    }

    // public function restore(Organization $organization)
    // {
    //     $organization->restore();

    //     return Redirect::back()->with('success', 'Organization restored.');
    // }
}
