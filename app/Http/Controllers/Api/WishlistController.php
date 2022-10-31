<?php

namespace App\Http\Controllers\Api;

use App\Models\Post;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\GratitudeResource;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Requests\Gratitude\StoreGratitudeRequest;

class WishlistController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $wishlist = $user->wishlist()->where('gratitude_id', null)->with('post', 'gratitude')->get();
        return jsonResponse(true, 'Wishlist retrieved successfully', $wishlist);
    }

    public function indexGratituded()
    {
        $user = auth()->user();
        $wishlist = $user->wishlist()->where('gratitude_id', '!=', null)->with('post', 'gratitude')->get();
        return jsonResponse(true, 'Wishlist retrieved successfully', $wishlist);
    }

    public function store(Post $post)
    {
        $user = auth()->user();
        $wishlist = $user->wishlist()->where('post_id', $post->id)->first();
        if ($wishlist) {
            return \jsonResponse(false, 'Post already in wishlist');
        }
        $user->wishlist()->create([
            'post_id' => $post->id
        ]);
        return \jsonResponse(true, 'Post added to wishlist');
    }

    public function gratitude(Wishlist $wish, StoreGratitudeRequest $request)
    {
        $user = auth()->user();
        $wish = $user->wishlist()->where('id', $wish->id)->first();
        if (!$wish) {
            return \jsonResponse(false, 'Post not in wishlist');
        }

        if ($wish->gratitude) {
            return \jsonResponse(false, 'Post already has gratitude');
        }

        $gratitude = $wish->gratitude()->create(
            $request->validated()
            + [ 'user_id' => $user->id ]
            + [ 'post_id' => $wish->post_id ]
        );

        $wish->update(
            [
                'gratitude_id' => $gratitude->id
            ]
        );

        $gratitude->attachTags($request->tags);

        return jsonResponse(true,'Gratitude created successfully' , new GratitudeResource($gratitude));
    }

    public function destroy($id)
    {
        $user = auth()->user();
        $wish = $user->wishlist()->where('post_id', $id)->first();
        if ($wish) {
            $wish->delete();
            return \jsonResponse(true, 'Post removed from wishlist');
        }
        return \jsonResponse(false, 'Post not in wishlist');
    }
}
