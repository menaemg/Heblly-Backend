<?php

namespace App\Http\Controllers\Api;

use App\Models\Post;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Api\Report\ReportRequest;

class ReportController extends Controller
{
    public function report(Post $post, ReportRequest $request)
    {
        // if($post->user_id == Auth::id()) {
        //     return \jsonResponse(false, 'you can\'t report your post', null, 401);
        // }

        $post->report()->create($request->validated()+ ['user_id' => Auth::id()]);

        return jsonResponse(true, 'report sent successfully');
    }
}
