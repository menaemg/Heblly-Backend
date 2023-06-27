<?php

namespace App\Http\Controllers\Api;

use App\Models\Gift;
use App\Models\Post;
use App\Models\Gratitude;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\api\report\ReportRequest;
use Illuminate\Support\Facades\Auth;

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
