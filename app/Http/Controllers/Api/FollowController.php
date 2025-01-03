<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Notifications\FollowAcceptNotification;
use App\Notifications\FollowRequestNotification;

class FollowController extends Controller
{
    public function followings()
    {
        $followings = Auth::user()->followings;

        $data = [
            'followings_count' => 0,
            'followings' => [],
        ];

        if ($followings) {
            $followings = $followings->load('followable:id,username')->pluck('followable');
            $followings = $followings->filter(function ($user) {
                return $user != null;
            })->map(function ($user) {
                if ($user != null) {
                    return [
                        'id' => $user->id,
                        'username' => $user->username,
                        'name' => $user->profile ? $user->profile->name : null,
                        'avatar'   => $user->profile->avatar_url ?? null,
                        'cover'   => $user->profile->cover_url ?? null,
                        'follower_count' => $user->followers->count(),
                    ];
                }
                return false;
            });

            $data = [
                'followings_count' => $followings->count(),
                'followings' => array_values($followings->toArray()),
            ];
        }

        return jsonResponse(true, "Followings List", $data);
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

        $followers = $followers->map(function ($user) {
            return [
                'id' => $user->id,
                'username' => $user->username,
                'name' => $user->profile ? $user->profile->name : null,
                'avatar'   => $user->profile->avatar_url ?? null,
                'cover'   => $user->profile->cover_url ?? null,
                'is_approved' => $user->pivot->accepted_at ? true : false,
                'follower_count' => $user->followers->count(),
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

            $user->notify(new FollowRequestNotification(Auth::user()));

            return jsonResponse(true, "Follow Request Sent");
        } catch (\Exception $e) {
            return jsonResponse(false, "Unable to send follow request (" . $e->getMessage() .")");
        }
    }

    public function unfollow(User $user)
    {
        try {
            if (!Auth::user()->isFollowing($user) && !Auth::user()->hasRequestedToFollow($user)) {
                return jsonResponse(false, "You are not following this user");
            }
            Auth::user()->unfollow($user);
            return jsonResponse(true, 'User unfollowed successfully');
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

            $user->notify(new FollowAcceptNotification(Auth::user()));

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

        if ($followings) {
            $followings = $followings->map(function ($following) {
                if ($following){
                    return [
                        'id' => $following->id,
                        'username' => $following->username,
                        'avatar'   => $following->profile->avatar_url ?? null,
                    ];
                }
            });
        }

        $followers = Auth::user()->approvedFollowers;


            $followers = $followers->map(function ($follower) {
                if ($follower) {
                    return [
                        'id' => $follower->id,
                        'username' => $follower->username,
                        'avatar'   => $follower->profile->avatar_url ?? null,
                        'cover' => $follower->profile->cover_url ?? null,
                        'followers_count' => $follower->followers()->count(),
                    ];
                }
            });

        $friends = $followings->concat($followers);

        $friends = $friends->filter(function ($friend) {
            return $friend != null;
        });

        $friends = $friends->filter(function ($friend) use ($request) {
            return false != stristr($friend['username'], $request->search);
        })->unique('id');

        // dd($friends, $followings, $followers);
        return jsonResponse(true, "Friends List", array_values($friends->toArray()));
    }

    public function users(Request $request)
    {
        $users = User::whereNotIn('id', auth()->user()->friends_ids)->when($request->has('search') && $request->search, function ($q) use($request) {
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
                'cover'   => $user->profile->cover_url ?? null,
                'followers_count' => $user->followers()->count(),
                'follow_request' => auth()->user()->hasRequestedToFollow($user),
            ];
        });

        return jsonResponse(true, "Users List", $users);
    }
}
