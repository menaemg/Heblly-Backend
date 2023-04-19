<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\User;
use Inertia\Inertia;
use App\Http\Controllers\Controller;
use App\Models\Post;

class DashboardController extends Controller
{
    public function index()
    {
        // $users_counts = User::count();
        // dd($users_counts);
        $counts = [
            'users' =>  User::count(),
            'picks' =>  Post::where('type', 'pick')->count(),
            'gratitude' =>  Post::where('type', 'gratitude')->count(),
            'wishes' =>  Post::where('type', 'wish')->count(),
            'gifts' =>  Post::where('type', 'gift')->count(),
        ];
        return Inertia::render('Dashboard/Index', compact('counts'));
    }
}
