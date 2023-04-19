<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Post;
use App\Models\User;
use Inertia\Inertia;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\Dashboard\Profile\StoreProfileRequest;
use App\Http\Requests\Dashboard\Profile\UpdateProfileRequest;

class GratitudeController extends Controller
{
    public function index()
    {
        // return Inertia::render('Picks/Index', [
        //     'filters' => Request::all('search', 'trashed'),
        //     'picks' => Post::where('type', 'pick')
        //         // ->withQueryString()
        //         // ->with('user')
        //         ->filter(Request::only('search', 'trashed'))
        //         ->paginate(10)
        //         ->withQueryString()
        //         ->through(fn ($pick) => [
        //             'id' => $pick->id,
        //             'title' => $pick->title,
        //             'location' => $pick->location,
        //             'main_image' => $pick->main_image,
        //         ]),
        // ]);

        // dd('yes');

        return Inertia::render('Gratitude/Index', [
            'filters' => Request::all('search', 'trashed'),
            'gratitude' => Post
                ::where('type', 'gratitude')
                ->with('user')
                ->searchFilter(Request::only('search', 'trashed'))
                ->withCount(['likers', 'comments'])
                ->paginate(10)
                ->withQueryString()
                ->through(fn ($pick) => [
                    'id' => $pick->id,
                    'title' => $pick->title,
                    'main_image' => $pick->main_image,
                    'user' => $pick->user,
                    'location' => $pick->location,
                    'likes' => $pick->likers_count,
                    'comments' => $pick->comments_count,
                    'tags' => $pick->tags->map(function ($tag) {
                        return $tag->name;
                    }),
                    'created_at' => $pick->created_at,
                    'gratitude_to'        => $pick->from_friend,
                ])
                ,
        ]);
    }

    public function destroy(Post $gratitude)
    {
        $gratitude->delete();

        return Redirect::route('picks.index')->with('success', 'Gratitude deleted.');
    }
}
