<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Notifications\GiftNotification;
use App\Http\Resources\Gift\GiftResource;
use App\Http\Requests\Gift\GiftStoreRequest;
use App\Http\Requests\Gift\GiftUpdateRequest;
use App\Notifications\GiftApproveNotification;

class GiftController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();

        $gift = $user->posts()->where('type', 'gift')->where('action', 'approved')->with('tags')->get();

        return jsonResponse(true, "Gift list retrieved successfully", GiftResource::collection($gift));
    }

    public function pending()
    {
        $user = auth()->user();

        $gift = $user->posts()->where('type', 'gift')->where('action', 'pending')->with('tags')->get();

        return jsonResponse(true, "Pending Gift list retrieved successfully", GiftResource::collection($gift));
    }

    public function rejected()
    {
        $user = auth()->user();

        $gift = $user->posts()->where('type', 'gift')->where('action', 'rejected')->with('tags')->get();

        return jsonResponse(true, "Rejected Gift list retrieved successfully", GiftResource::collection($gift));
    }

    public function approveGift(Post $post)
    {
        $user = auth()->user();

        $gift = $user->posts()->where('type', 'gift')->where('id', $post->id)->with('tags')->first();

        if(!$gift) {
            return jsonResponse(false, "Gift not found");
        }

        $post->update([
            'action' => 'approved'
        ]);

        $user->notify(new GiftApproveNotification($gift->from_friend, $gift));

        return jsonResponse(true, "Gift approved successfully", new GiftResource($post));
    }

    public function rejectGift(Post $post)
    {
        $user = auth()->user();

        $gift = $user->posts()->where('type', 'gift')->where('id', $post->id)->with('tags')->first();

        if(!$gift) {
            return jsonResponse(false, "Gift not found");
        }

        $post->update([
            'action' => 'rejected'
        ]);

        return jsonResponse(true, "Gift rejected successfully", new GiftResource($post));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(GiftStoreRequest $request)
    {
        $forUser = User::findOrFail($request->for_id);

        $gift = $forUser->posts()->create($request->validated() + ['type' => 'gift'] + ['action' => 'pending'] + ['from_id' => auth()->user()->id]);

        $forUser->notify(new GiftNotification(Auth::user(), $gift));

        return jsonResponse(true, "Gift Created", new GiftResource($gift));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Post $gift)
    {
        if ((Auth::user()->id == $gift->user_id  || $gift->privacy == 'public') && ($gift->type == 'gift' )) {
            return jsonResponse(true, "User Gift", new GiftResource($gift));
        }

        return jsonResponse(false, "You are not authorized to view this gift", null, 403);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Post $gift, GiftUpdateRequest $request)
    {
        if (Auth::user()->id == $gift->user_id && ($gift->type == 'gift')) {
            $gift->update($request->validated());
            return jsonResponse(true, "Gift Updated", new GiftResource($gift));
        }

        return jsonResponse(false, "You are not authorized to update this gift", null, 403);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $gift)
    {
        if (Auth::user()->id == $gift->user_id && ($gift->type == 'gift')) {
            $gift->delete();
            return jsonResponse(true, "Gift Deleted", null);
        }

        return jsonResponse(false, "You are not authorized to delete this gift", null, 403);
    }
}
