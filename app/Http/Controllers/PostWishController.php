<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Termwind\Components\Dd;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\PostWishResource;
use App\Http\Requests\PostWishStoreRequest;
use App\Http\Requests\PostWishUpdateRequest;

class PostWishController extends Controller
{
        /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();

        $wishlist = $user->posts()->where('type', 'wish')->with('tags')->get();

        dd($wishlist);

        $wishlist = $user->wishlist()->with('post', 'gratitude')->get()->pluck('post')->concat($wishlist);


        return jsonResponse(true, "Your Wish list retrieved successfully", PostWishResource::collection($wishlist));
    }

    public function anotherUser(User $user)
    {
        if ($user->privacy == 'private') {
            return jsonResponse(false, "User privacy is set to private");
        }

        if ($user->privacy == 'friends') {
            if (!$friend->isFollowing($user) && !$friend->isFollowedBy($user)) {
                return jsonResponse(false, "you not friend with this user", null, 403);
            }
        }

        $wishlist = $user->posts()->where('type', 'wish')->where('privacy', 'public')->with('tags')->get();

        $wishlist = $user->wishlist()->where('privacy', 'public')->with('post', 'gratitude')->get()->pluck('post')->concat($wishlist);

        return jsonResponse(true, "User Wish list retrieved successfully", PostWishResource::collection($wishlist));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PostWishStoreRequest $request)
    {
        $wish = Auth::user()->posts()->create($request->validated() + ['type' => 'wish']);


        return jsonResponse(true, "Wish Created", new PostWishResource($wish));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Post $wish)
    {
        if ((Auth::user()->id == $wish->user_id  || $wish->privacy == 'public') && ($wish->type == 'wish' )) {
            return jsonResponse(true, "User Wish", new PostWishResource($wish));
        }

        return jsonResponse(false, "You are not authorized to view this wish", null, 403);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Post $wish, PostWishUpdateRequest $request)
    {
        if (Auth::user()->id == $wish->user_id && ($wish->type == 'wish')) {
            $wish->update($request->validated());
            return jsonResponse(true, "Wish Updated", new PostWishResource($wish));
        }

        return jsonResponse(false, "You are not authorized to update this wish", null, 403);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $wish)
    {
        if (Auth::user()->id == $wish->user_id && ($wish->type == 'wish')) {
            $wish->delete();
            return jsonResponse(true, "Wish Deleted", null);
        }

        return jsonResponse(false, "You are not authorized to delete this wish", null, 403);
    }
}
