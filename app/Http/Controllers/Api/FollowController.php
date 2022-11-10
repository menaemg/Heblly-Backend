<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Termwind\Components\Dd;

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
                'name' => $follower->profile->first_name . ' ' . $follower->profile->last_name,
                'username' => $follower->username,
                'email' => $follower->email,
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
                'name' => $follower->profile->first_name . ' ' . $follower->profile->last_name,
                'username' => $follower->username,
                'email' => $follower->email,
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
                'name' => $follower->profile->first_name . ' ' . $follower->profile->last_name,
                'username' => $follower->username,
                'email' => $follower->email,
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

    public function friends()
    {
        $followings = Auth::user()->approvedFollowings->load('followable:id,username')->pluck('followable');

        $followers = Auth::user()->approvedFollowers->load('followable:id,username')->pluck('followable');


    }

}
