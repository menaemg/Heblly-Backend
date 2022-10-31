<?php

namespace App\Http\Controllers\Api;

use App\Models\Post;
use App\Models\User;
use App\Models\Wishboard;
use Termwind\Components\Dd;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class WishboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $wishboard = $user->wishboard()->with('post', 'fromUser')->get();
        return jsonResponse(true, 'Wishboard retrieved successfully', $wishboard);
    }

    public function addWishToFriend(Post $post, Request $request)
    {
        $user = auth()->user();

        $friend = User::where('id', $request->friend_id)->firstOrFail();

        $isFriend = $user->isFollowing($friend);

        if (!$isFriend) {
            return jsonResponse(false, 'You are not Friend this user');
        }

        $wish = $friend->wishboard()->where('post_id', $post->id)->where('from_user_id', $user->id)->first();

        if ($wish) {
            return jsonResponse(false, 'Post already in wishboard');
        }

        $friend->wishboard()->create([
            'post_id' => $post->id,
            'from_user_id' => $user->id
        ]);

        return jsonResponse(true, 'Post added to friend wishlist');
    }

    public function addToWishList(Wishboard $wish)
    {
        $user = auth()->user();

        $wish = $user->wishboard()->where('id', $wish->id)->first();

        if (!$wish) {
            return jsonResponse(false, 'Post not in wishboard');
        }

        if ($wish->gratitude) {
            return jsonResponse(false, 'Post already has gratitude');
        }

        $user->wishlist()->create([
            'post_id' => $wish->post_id
        ]);

        $wish->delete();

        return jsonResponse(true, 'Post added to wishlist');
    }

    public function destroy(Wishboard $wish)
    {
        $user = auth()->user();

        $wish = $user->wishboard()->where('id', $wish->id)->first();

        if (!$wish) {
            return jsonResponse(false, 'Post not in wishboard');
        }

        $wish->delete();

        return jsonResponse(true, 'Post removed from wishboard');

    }
}
