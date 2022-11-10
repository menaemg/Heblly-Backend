<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Termwind\Components\Dd;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\ProfileResource;
use App\Http\Requests\Api\Profile\ProfileRequest;

class ProfileController extends Controller
{
    public function authProfile()
    {
        $profile = User::withCount(['followings', 'followables'])
                            ->with(['profile'])->where('id', auth()->id())->first();

        $profileResource = new ProfileResource($profile);

        return jsonResponse(true, 'Profile', $profileResource);
    }

    public function profile(User $user)
    {
        if($user->isBlocked($user)) {
            return jsonResponse(false, 'User is blocked', null, 403);
        }

        $profile = User::withCount(['followings', 'followables'])
                            ->with(['profile'])->where('id', $user->id)->first();

        $profileResource = new ProfileResource($profile);

        return jsonResponse(true, 'Profile', $profileResource);
    }

    public function updateProfile(ProfileRequest $request)
    {
        $userRequest = $request->safe()->only(['username', 'email']);
        $profileRequest = $request->safe()->except(['username', 'email']);

        $user = User::where('id', auth()->user()->id)->withCount('followings', 'followables')->first();


        if (!empty($userRequest)) {
            $user->update($userRequest);
        }

        if (!empty($profileRequest)) {

            if($request->has('avatar') && \is_file($request->file('avatar'))){

                $profileRequest['avatar'] = $this->uploadImage($request->file('avatar') , 'avatars');

                if($user->profile && $user->profile->avatar && $profileRequest['avatar']){
                    $this->deleteImage($user->profile->avatar , 'avatars');
                }

            }

            if($request->has('cover') && \is_file($request->file('cover'))){
                $profileRequest['cover'] = $this->uploadImage($request->file('cover'), 'covers');

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

        $user = User::where('id', auth()->user()->id)->withCount('followings', 'followables')->first();

        $profileResource = new ProfileResource($user);

        return jsonResponse(true, 'Profile', $profileResource);
    }
}
