<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Resources\CommentResource;

class CommentController extends Controller
{
    public function index(Post $post)
    {
        $comments = $post->comments()->with('commentator:id,username')->with('commentator.profile:id,avatar')->get();

        $comments = CommentResource::collection($comments);

        return jsonResponse(true, "Comments List", $comments);
    }
    public function store(Post $post, Request $request)
    {
        if (!$request->has('comment') || !$request->comment) {
            return jsonResponse(false, "Comment Is Required", null, 422);
        }

        $user = auth()->user();

        $comment = $post->commentAsUser($user, $request->comment);

        return jsonResponse(true, "Comment Added", $comment);
    }

    public function destroy(Post $post, $commentId)
    {
        $comment = $post->comments()->where('id', $commentId)->first();

        if (!$comment) {
            return jsonResponse(false, "Comment Not Found", null, 404);
        }



        if ($comment->user_id == auth()->id() || $post->user_id == auth()->id()) {
            $comment->delete();
            return jsonResponse(true, "Comment Deleted");
        }

        return jsonResponse(false, "You Are Not Allowed To Delete This Comment", null, 403);
    }
}
