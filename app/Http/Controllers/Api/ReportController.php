<?php

namespace App\Http\Controllers\Api;

use App\Models\Gift;
use App\Models\Post;
use App\Models\Gratitude;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function report(Post $post, Request $request)
    {
        // if($post->user_id == Auth::id()) {
        //     return \jsonResponse(false, 'you can\'t report you Pick', null, 401);
        // }

        $post->report()->create([
            'reason' => 'reason',
            'description' => 'desc',
            'user_id' => Auth::id()
        ]);

        return jsonResponse(true, 'report sent successfully');
    }
}
