<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreGratitudeRequest;
use App\Notifications\GratitudeNotification;
use App\Http\Requests\UpdateGratitudeRequest;
use App\Http\Resources\Gratitude\GratitudeResource;

class GratitudeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $gratitudes = Auth::user()->posts()->where('type', 'gratitude')->with('tags', 'from_friend')->get();


        return jsonResponse(true, "User Gratitudes", GratitudeResource::collection($gratitudes));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreGratitudeRequest $request)
    {
        $gratitude = Auth::user()->posts()->create($request->validated() + ['type' => 'gratitude']);

        User::find($request->from_id)->notify(new GratitudeNotification(Auth::user(), $gratitude));

        return jsonResponse(true, "Gratitude Created", new GratitudeResource($gratitude));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Post $gratitude)
    {
        if (( Auth::user()->id == $gratitude->user_id || Auth::user()->id == $gratitude->from_id  || $gratitude->privacy == 'public') && ($gratitude->type == 'gratitude' )) {
            return jsonResponse(true, "User Gratitude", new GratitudeResource($gratitude));
        }

        return jsonResponse(false, "You are not authorized to view this gratitude", null, 403);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Post $gratitude, UpdateGratitudeRequest $request)
    {
        if (Auth::user()->id == $gratitude->user_id && ($gratitude->type == 'gratitude')) {
            $gratitude->update($request->validated());
            return jsonResponse(true, "Gratitude Updated", new GratitudeResource($gratitude));
        }

        return jsonResponse(false, "You are not authorized to update this gratitude", null, 403);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $gratitude)
    {
        if (Auth::user()->id == $gratitude->user_id && ($gratitude->type == 'gratitude' || $gratitude->type == 'post')) {
            $gratitude->delete();
            return jsonResponse(true, "Gratitude Deleted", null);
        }

        return jsonResponse(false, "You are not authorized to delete this gratitude", null, 403);
    }
}
