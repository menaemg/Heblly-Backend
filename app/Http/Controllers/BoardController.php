<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\BoardNotification;
use App\Http\Resources\Gift\GiftResource;
use App\Http\Requests\Gift\GiftStoreRequest;
use App\Http\Requests\Gift\GiftUpdateRequest;

class BoardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();

        $gift = $user->posts()->where('type', 'board')->with('tags')->get();

        return jsonResponse(true, "Board list retrieved successfully", GiftResource::collection($gift));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(GiftStoreRequest $request)
    {
        $user = auth()->user();
        $friend = User::findOrFail($request->for_id);

        if (!$friend->isFollowing($user) && !$friend->isFollowedBy($user)) {
            return jsonResponse(false, "you are not friend with this user", null, 403);
        }

        $board = $friend->posts()->create($request->validated() + ['type' => 'board'] + ['from_id' => $user->id]);

        $friend->notify(new BoardNotification($user, $board));

        return jsonResponse(true, "Added to board friend", new GiftResource($board));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Post $board)
    {
        if ((Auth::user()->id == $board->user_id  || $board->privacy == 'public') && ($board->type == 'board' )) {
            return jsonResponse(true, "User Board", new GiftResource($board));
        }

        return jsonResponse(false, "You are not authorized to view this pick", null, 403);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function update(Post $gift, GiftUpdateRequest $request)
    // {
    //     if (Auth::user()->id == $gift->user_id && ($gift->type == 'gift')) {
    //         $gift->update($request->validated());
    //         return jsonResponse(true, "Gift Updated", new GiftResource($gift));
    //     }

    //     return jsonResponse(false, "You are not authorized to update this gift", null, 403);
    // }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $board)
    {
        if (Auth::user()->id == $board->user_id && ($board->type == 'board')) {
            $board->delete();
            return jsonResponse(true, "Pick Deleted", null);
        }

        return jsonResponse(false, "You are not authorized to delete this pick", null, 403);
    }
}
