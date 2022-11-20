<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use App\Notifications\ExtendNotification;
use App\Notifications\ReserveNotification;
use Termwind\Components\Dd;

class ReserveController extends Controller
{
    public function index()
    {
        $reserves = auth()->user()->reserves()->where('status', 'pending')->with('post')->get()->pluck('post');
        return jsonResponse(true, 'Active Reserves retrieved successfully', $reserves);
    }

    public function reserve(Post $post)
    {
        $user = auth()->user();

        if ($post->user_id == auth()->id()) {
            return jsonResponse(false, 'You can not reserve your own wish');
        }

        if ($post->reserved) {
            if ($post->reserved->user_id == auth()->id()) {

                if ($post->reserved->status == 'granted') {
                    return jsonResponse(false, 'You have already granted this wish');
                }

                if ($post->reserved->status == 'released') {
                    return jsonResponse(false, 'You can\'t reserve this wish again');
                }

                return jsonResponse(false, 'You already reserved this wish');
            }

            if (!$post->reserved->status == 'released') {
                return jsonResponse(false, 'you late, someone already has reserved this wish');
            }
        }

        if (!$post->type == 'wish' || !$post->type == 'board') {
            return jsonResponse(false, 'Gift not available');
        }

        if (!$post->user->isFollowing($user) && !$post->user->isFollowedBy($user)) {
            return jsonResponse(false, "You Need to Follow User First", null, 403);
        }

        if ($user->reserves->count() >= 3) {
            return jsonResponse(false, 'You can not reserve more than 3 gifts, grant or cancel one of your reserves first');
        }

        $user_reserved = $user->reserves()->whereHas('post', function ($query) use ($post) {
            $query->where('user_id', $post->user_id)->where('status', 'pending');
        })->get()->count();

        if ($user_reserved) {
            return jsonResponse(false, 'You already reserved a gift for this user, please grant it first');
        }

        $user->reserves()->create([
            'post_id' => $post->id,
        ]);

        $user->notify(new ReserveNotification($user, $post));

        return jsonResponse(true, 'Gift reserved successfully', $post);
    }

    public function extend(Post $post)
    {
        if (!$post->reserved) {
            return jsonResponse(false, 'Gift not reserved yet');
        }

        if ($post->reserved->user_id != auth()->id() || $post->reserved->status == 'released') {
            return jsonResponse(false, 'You can not extended this reserve');
        }

        if ($post->reserved->status == 'extended') {
            return jsonResponse(false, 'You already extended this gift');
        }

        if (!Carbon::parse($post->reserved->created_at)->addDays(14)->isPast()) {
            return jsonResponse(false, 'You can not extended this reserve');
        }

        $post->reserved->update([
            'status' => 'extended',
        ]);
    }

    public function testNotification() {
        $post = Post::find(19);
        $user = User::find(2);
        $user->notify(new ExtendNotification($user, $post));
    }


    public function granted(Post $post, Request $request) {

        if (!$post->reserved) {
            return jsonResponse(false, 'Gift not reserved yet');
        }

        if ($post->reserved->user_id != auth()->id()) {
            return jsonResponse(false, 'You can not granted this reserve');
        }

        if ($post->reserved->status == 'granted') {
            return jsonResponse(false, 'You already granted this gift');
        }

        if ($request->notification_id) {
            $notification = auth()->user()->notifications()->where('id',$request->notification_id)->first();
            if ($notification) {
                $notification->delete();
            }
        }


        $post->reserved->update([
            'status' => 'granted',
        ]);



        auth()->user()->not;

        return jsonResponse(true, 'Gift granted successfully', $post);
    }

    public function release(Post $post)
    {
        if (!$post->reserved) {
            return jsonResponse(false, 'Gift not reserved yet');
        }

        if ($post->reserved->user_id != auth()->id()) {
            return jsonResponse(false, 'You can not released this reserve');
        }

        if ($post->reserved->status == 'released') {
            return jsonResponse(false, 'You already released this gift');
        }

        $post->reserved->update([
            'status' => 'released',
        ]);

        return jsonResponse(true, 'Reserve released successfully');
    }
}
