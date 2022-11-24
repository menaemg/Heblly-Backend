<?php

namespace App\Http\Controllers\Api;

use App\Models\Post;
use Spatie\Tags\Tag;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Post\StorePostRequest;
use App\Http\Requests\Post\UpdatePostRequest;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $posts = Auth::user()->posts()->with('tags' , 'user.profile')->latest()->get();

        return jsonResponse(true, 'Posts retrieved successfully', PostResource::collection($posts));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePostRequest $request)
    {
        $user = auth()->user();

        $post = Post::create(
            $request->validated()
            + [ 'user_id' => $user->id ]
        );

        $post->attachTags($request->tags);

        return jsonResponse(true,'Post created successfully' , new PostResource($post));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show($post)
    {
        $post = Post::where('id', $post)->orWhere('slug', $post)->with('user.profile')->firstOrFail();

        return jsonResponse('Post retrieved successfully', new PostResource($post));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePostRequest $request, $post)
    {
        $post = Post::where('id', $post)->orWhere('slug', $post)->firstOrFail();

        $post->update($request->validated());

        if ($request->has('tags')) {
            $post->syncTags($request->tags);
        }

        return jsonResponse(true, 'Post updated successfully', new PostResource($post));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy($post)
    {
        $post = Post::where('id', $post)->orWhere('slug', $post)->firstOrFail();

        $post->delete();

        return jsonResponse(true, 'Post deleted successfully', $post);
    }

    public  function landingPosts(Request $request)
    {
        $user = $request->user('sanctum');

        $per_page = $request->per_page && \is_numeric($request->per_page) ? $request->per_page : 13;

        $posts = Post::where('privacy', 'public')->when($user, function($q, $user) {
            $q->whereNot('user_id', $user->id);
        })
        ->when($request->has('search') && $request->search, function ($q) use($request) {
            $q->where('title', 'like', '%' . $request->search . '%');
        })
        ->with('tags' , 'user.profile')->latest()->paginate($per_page);

        return jsonResponse(true, 'Posts retrieved successfully', PostResource::collection($posts)->response()->getData());
    }

    public  function friendsPosts(Request $request)
    {
        $friends = Auth::user()->approvedFollowers()->pluck('user_id');
        $friends = Auth::user()->approvedFollowings()->pluck('user_id')->merge($friends);

        $per_page = $request->per_page && \is_numeric($request->per_page) ? $request->per_page : 13;

        $posts = Post::whereIn('user_id', $friends)->with('tags')->latest()->paginate($per_page);

        return jsonResponse(true, 'Posts retrieved successfully', PostResource::collection($posts)->response()->getData());
    }
}
