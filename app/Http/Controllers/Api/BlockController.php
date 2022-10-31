<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BlockController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $block = $user->blocklist()->with('blockUser')->get();
        return jsonResponse(true, 'Block retrieved successfully', $block);
    }

    public function block(User $user)
    {
        $auth_user = auth()->user();

        if ($auth_user->id == $user->id) {
            return jsonResponse(false, 'You can not block yourself');
        }

        $block = $auth_user->blocklist()->where('block_user_id', $user->id)->first();
        if ($block) {
            return jsonResponse(false, 'User already in blocklist');
        }
        $auth_user->blocklist()->create([
            'block_user_id' => $user->id
        ]);
        return jsonResponse(true, 'User added to blocklist');
    }

    public function unblock(User $user)
    {
        $auth_user = auth()->user();

        $block = $auth_user->blocklist()->where('block_user_id', $user->id)->first();
        if (!$block) {
            return jsonResponse(false, 'User not in blocklist');
        }
        $block->delete();
        return jsonResponse(true, 'User removed from blocklist');
    }
}
