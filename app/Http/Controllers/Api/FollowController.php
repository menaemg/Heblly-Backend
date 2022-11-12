<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class FollowController extends Controller
{
    public function followings()
    {
        $followings = Auth::user()->followings->load('followable:id,username')->pluck('followable');

        return jsonResponse(true, "Followings List", $followings);
    }

    public function approvedFollowings()
    {
        $followings = Auth::user()->approvedFollowings->load('followable:id,username')->pluck('followable');

        return jsonResponse(true, "Approved Followings List", $followings);
    }

    public function notApprovedFollowings()
    {
        $followings = Auth::user()->notApprovedFollowings->load('followable:id,username')->pluck('followable');

        return jsonResponse(true, "Not Approved Followings List", $followings);
    }

    public function followers()
    {
        $followers = Auth::user()->followers;

        $followers = $followers->map(function ($follower) {
            return [
                'id' => $follower->id,
                'name' => $follower->profile->name ?? null,
                'username' => $follower->username,
                'email' => $follower->email ?? null,
                'is_approved' => $follower->pivot->accepted_at ? true : false,
            ];
        });

        $data = [
            'followers_count' => $followers->count(),
            'followers' => $followers,
        ];

        return jsonResponse(true, "Followers", $data);
    }

    public function approvedFollowers()
    {
        $followers = Auth::user()->approvedFollowers;

        $followers = $followers->map(function ($follower) {
            return [
                'id' => $follower->id,
                'name' => $follower->profile->name ?? null,
                'username' => $follower->username,
                'email' => $follower->email ?? null,
            ];
        });

        $data = [
            'followers_count' => $followers->count(),
            'followers' => $followers,
        ];

        return jsonResponse(true, "Approved Followers", $data);
    }


    public function notApprovedFollowers()
    {
        $followers = Auth::user()->notApprovedFollowers;

        $followers = $followers->map(function ($follower) {
            return [
                'id' => $follower->id,
                'name' => $follower->profile->name ?? null,
                'username' => $follower->username,
                'email' => $follower->email ?? null,
            ];
        });

        $data = [
            'followers_count' => $followers->count(),
            'followers' => $followers,
        ];

        return jsonResponse(true, "Not Approved Followers", $data);
    }

    public function follow(User $user)
    {
        try {
            Auth::user()->follow($user);
            return jsonResponse(true, "Follow Request Sent");
        } catch (\Exception $e) {
            return jsonResponse(false, "Unable to send follow request (" . $e->getMessage() .")");
        }
    }

    public function unfollow(User $user)
    {
        try {
            Auth::user()->unfollow('User unfollowed successfully');
            return jsonResponse(true, "Unfollowed");
        } catch (\Exception $e) {
            return jsonResponse(false, "Unable to unfollow (" . $e->getMessage() .")");
        }
    }

    public function acceptFollowRequest(User $user)
    {
        try {
            $followRequest = Auth::user()->notApprovedFollowers->where('id', $user->id)->first();

            if (!$followRequest) {
                return jsonResponse(false, "Follow request not found");
            }
            Auth::user()->acceptFollowRequestFrom($user);
            return jsonResponse(true, "Follow Request Accepted");
        } catch (\Exception $e) {
            return jsonResponse(false, "Unable to accept follow request (" . $e->getMessage() .")");
        }
    }

    public function rejectFollowRequest(User $user)
    {
        try {
            Auth::user()->rejectFollowRequest($user);
            return jsonResponse(true, "Follow Request Rejected");
        } catch (\Exception $e) {
            return jsonResponse(false, "Unable to reject follow request (" . $e->getMessage() .")");
        }
    }

    public function friends(Request $request)
    {
        $followings = Auth::user()->approvedFollowings->load('followable:id,username')->pluck('followable');

        $followings = $followings->map(function ($following) {
            return [
                'id' => $following->id,
                'username' => $following->username,
                'avatar'   => $following->profile->avatar_url ?? null,
            ];
        });

        $followers = Auth::user()->approvedFollowers;

        $followers = $followers->map(function ($follower) {
            return [
                'id' => $follower->id,
                'username' => $follower->username,
                'avatar'   => $follower->profile->avatar_url ?? null,
            ];
        });



        $friends = $followings->concat($followers);
        $friends = $friends->filter(function ($friend) use ($request) {
            return false != stristr($friend['username'], $request->search);
        })->unique('id');

        return jsonResponse(true, "Friends List", $friends);
    }

    public function users(Request $request)
    {
        // dd($request->has('search'), $request->search);
        $users = User::when($request->has('search') && $request->search, function ($q) use($request) {
            $q->where('username', 'like', '%' . $request->search . '%');
        })->where('id', '!=', Auth::id())
        ->doesntHave('profile')->OrWhereHas('profile', function ($q) {
            $q->where('privacy', 'public');
        })
        ->when($request->has('search') && $request->search, function ($q) use($request) {
            $q->where('username', 'like', '%' . $request->search . '%');
        })
        ->get();

        $users = $users->map(function ($user) {
            return [
                'id' => $user->id,
                'username' => $user->username,
                'avatar'   => $user->profile->avatar_url ?? null,
            ];
        });

        return jsonResponse(true, "Users List", $users);
    }
}
