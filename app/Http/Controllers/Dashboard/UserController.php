<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\User;
use Inertia\Inertia;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\Dashboard\Profile\StoreProfileRequest;
use App\Http\Requests\Dashboard\Profile\UpdateProfileRequest;

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
                    'status' => $user->status,
                    // 'deleted_at' => $organization->deleted_at,
                ]),
        ]);
    }

    public function create()
    {
        return Inertia::render('Users/Create');
    }

    public function store(StoreProfileRequest $request)
    {
        $userRequest = $request->safe()->only(['username', 'email', 'password','type']);
        $profileRequest = $request->safe()->except(['username', 'email', 'password', 'type', 'avatar_file', 'cover_file']);

        $user = User::create($userRequest);

        if (!empty($profileRequest)) {

            if($request->has('avatar_file') && \is_file($request->file('avatar_file'))){
                $profileRequest['avatar'] = $this->uploadImage($request->file('avatar_file') , 'avatars');
            }

            if($request->has('cover_file') && \is_file($request->file('cover_file'))){
                $profileRequest['cover'] = $this->uploadImage($request->file('cover_file'), 'covers');

            }

            $user->profile()->create($profileRequest);
        }

        return Redirect::route('users')->with('success', 'User created.');
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
                'type' => $user->type,
                'status' => $user->status,
            ],
        ]);
    }

    public function update(User $user, UpdateProfileRequest $request)
    {
        $userRequest = $request->safe()->only(['username', 'email', 'password', 'type']);
        $profileRequest = $request->safe()->except(['username', 'email', 'password', 'type', 'avatar_file', 'cover_file']);

        if (!empty($userRequest)) {
            $user->update($userRequest);
        }

        if (!empty($profileRequest)) {

            if($request->has('avatar_file') && \is_file($request->file('avatar_file'))){
                $profileRequest['avatar'] = $this->uploadImage($request->file('avatar_file') , 'avatars');

                if($user->profile && $user->profile->avatar && $profileRequest['avatar']){
                    $this->deleteImage($user->profile->avatar , 'avatars');
                }

            }

            if($request->has('cover_file') && \is_file($request->file('cover_file'))){
                $profileRequest['cover'] = $this->uploadImage($request->file('cover_file'), 'covers');

                if($user->profile->cover && $profileRequest['cover']){
                    $this->deleteImage($user->profile->cover, 'covers');
                }
            }

            if($user->profile){
                $user->profile()->update($profileRequest);
            }else{
                $user->profile()->create($profileRequest);
            }

        }

        return Redirect::back()->with('success', 'User updated.');
    }


    public function disableUser(User $user)
    {
        $user->update([
            'status' => 'disable',
        ]);

        $user->tokens()->delete();

        return Redirect::back()->with('success', 'User disabled.');
    }

    public function activeUser(User $user)
    {
        $user->update([
            'status' => 'active',
        ]);

        return Redirect::back()->with('success', 'User Activated.');
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
