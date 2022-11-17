<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Notifications\PostLikeNotification;

class LikeController extends Controller
{
    public function like(Post $post)
    {
        Auth()->user()->like($post);

        if (Auth()->user()->id !== $post->user_id) {
            $post->user->notify(new PostLikeNotification(Auth()->user(), $post));
        }

        return jsonResponse(true, "Post Liked", $post, 200);
    }

    public function unlike(Post $post)
    {
        Auth()->user()->unlike($post);

        return jsonResponse(true, "Post Unliked", $post, 200);
    }

    public function toggleLike(Post $post)
    {

        Auth()->user()->toggleLike($post);

        $status = Auth()->user()->hasLiked($post) ? "Post Liked" : "Post Unliked";

        if (Auth()->user()->id !== $post->user_id && Auth()->user()->hasLiked($post)) {
            $post->user->notify(new PostLikeNotification(Auth()->user(), $post));
        }

        return jsonResponse(true, $status, $post, 200);
    }

    public function hasLiked(Post $post)
    {
        $status = Auth()->user()->hasLiked($post) ? true : false;

        return jsonResponse(true, 'Post Like Status', [
            'status' => $status
        ], 200);
    }

    public function postLikesCount(Post $post)
    {
        $count = $post->likers()->count();
        return jsonResponse(true, 'Post Likes Count', [
            'count' => $count
        ], 200);
    }

    public function userLikesCount()
    {
        $count = Auth()->user()->likers()->count();
        return jsonResponse(true, 'User Likes Count', [
            'count' => $count
        ], 200);
    }
}
