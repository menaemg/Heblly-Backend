<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Http\Requests\BlockRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\BlockResource;

class BlockController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $block = BlockResource::collection($user->blocklist);

        return jsonResponse(true, 'Block retrieved successfully', $block);
    }

    public function block(User $user, BlockRequest $request)
    {
        $auth_user = auth()->user();

        $type = $request->type ?? 'all';

        if ($auth_user->id == $user->id) {
            return jsonResponse(false, 'You can not block yourself');
        }

        $block = $auth_user->blocklist()->where('block_user_id', $user->id)->where('type', $type)->first();
        if ($block) {
            return jsonResponse(false, 'User already Blocked');
        }

        $auth_user->blocklist()->create([
            'block_user_id' => $user->id,
            'type' => $request->type ?? 'all',
        ]);

        return jsonResponse(true, 'User added to Blocked');
    }

    public function unblock(User $user, BlockRequest $request)
    {

        $auth_user = auth()->user();

        $type = $request->type ?? 'all';

        $block = $auth_user->blocklist()->where('block_user_id', $user->id)->where('type', $type)->first();
        if (!$block) {
            return jsonResponse(false, 'User not Blocked yet');
        }

        $block->delete();

        return jsonResponse(true, 'User Unblocked', );
    }
}
