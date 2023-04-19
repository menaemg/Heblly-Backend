<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Post;
use Inertia\Inertia;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Redirect;

class GiftController extends Controller
{
    public function index()
    {

        return Inertia::render('Gifts/Index', [
            'filters' => Request::all('search', 'trashed'),
            'gifts' => Post
                ::where('type', 'gift')
                ->with('user')
                ->searchFilter(Request::only('search', 'trashed'))
                ->withCount(['likers', 'comments'])
                ->paginate(10)
                ->withQueryString()
                ->through(fn ($gift) => [
                    'id' => $gift->id,
                    'title' => $gift->title,
                    'main_image' => $gift->main_image,
                    'user' => $gift->user,
                    'location' => $gift->location,
                    'likes' => $gift->likers_count,
                    'comments' => $gift->comments_count,
                    'tags' => $gift->tags->map(function ($tag) {
                        return $tag->name;
                    }),
                    'created_at' => $gift->created_at,
                    'gift_from'        => $gift->from_friend,
                ])
                ,
        ]);
    }

    public function destroy(Post $gift)
    {
        $gift->delete();

        return Redirect::route('gifts.index')->with('success', 'Gift deleted.');
    }
}
