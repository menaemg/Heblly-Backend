<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Notifications\ReserveNotification;

class ReserveController extends Controller
{
    public function index()
    {
        $reserves = auth()->user()->reserves()->with('post')->get()->pluck('post');
        return jsonResponse(true, 'Reserves retrieved successfully', $reserves);
    }

    public function reserve(Post $post)
    {
        $user = auth()->user();

        if ($post->user_id == auth()->id()) {
            return jsonResponse(false, 'You can not reserve your own wish');
        }

        if ($post->reserved) {
            if ($post->reserved->user_id == auth()->id()) {
                return jsonResponse(false, 'You already reserved this wish');
            }
            return jsonResponse(false, 'Gift already reserved');
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
            $query->where('user_id', $post->user_id);
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

    public function cancel(Post $post)
    {
        // dd($post->reserved);
        if (!$post->reserved) {
            return jsonResponse(false, 'Gift not reserved yet');
        }

        if ($post->reserved->user_id != auth()->id()) {
            return jsonResponse(false, 'You can not cancel this reserve');
        }

        $post->reserved->delete();

        return jsonResponse(true, 'Reserve canceled successfully');
    }
}
