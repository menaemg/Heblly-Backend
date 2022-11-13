<?php

namespace App\Http\Controllers\Api;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\PickResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StorePickRequest;
use App\Http\Requests\UpdatePickRequest;

class PickController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $picks = Auth::user()->posts()->where('type', 'post')->with('tags')->get();


        return jsonResponse(true, "User Picks", PickResource::collection($picks));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePickRequest $request)
    {
        $pick = Auth::user()->posts()->create($request->validated() + ['type' => 'pick']);

        return jsonResponse(true, "Pick Created", new PickResource($pick));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Post $pick)
    {
        if (Auth::user()->id == $pick->user_id && ($pick->type == 'pick' || $pick->type == 'post')) {
            return jsonResponse(true, "User Pick", new PickResource($pick));
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
    public function update(Post $pick, UpdatePickRequest $request)
    {
        if (Auth::user()->id == $pick->user_id && ($pick->type == 'pick' || $pick->type == 'post')) {
            $pick->update($request->validated());
            return jsonResponse(true, "Pick Updated", new PickResource($pick));
        }

        return jsonResponse(false, "You are not authorized to update this pick", null, 403);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $pick)
    {
        if (Auth::user()->id == $pick->user_id && ($pick->type == 'pick' || $pick->type == 'post')) {
            $pick->delete();
            return jsonResponse(true, "Pick Deleted", null);
        }

        return jsonResponse(false, "You are not authorized to delete this pick", null, 403);
    }
}
