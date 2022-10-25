<?php

namespace App\Http\Controllers\Api;

use App\Models\Gift;
use App\Http\Controllers\Controller;
use App\Http\Resources\GiftResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Gift\StoreGiftRequest;
use App\Http\Requests\Gift\UpdateGiftRequest;

class GiftController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $gifts = Auth::user()->gifts()->with('tags')->latest()->paginate(10);

        return jsonResponse(true, 'Gifts retrieved successfully', GiftResource::collection($gifts));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreGiftRequest $request)
    {
        $user = auth()->user();

        $gift = Gift::create(
            $request->validated()
            + [ 'user_id' => $user->id ]
        );

        $gift->attachTags($request->tags);

        return jsonResponse(true,'Gift created successfully' , new GiftResource($gift));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($gift)
    {
        $gift = Gift::where('id', $gift)->orWhere('slug', $gift)->firstOrFail();

        return jsonResponse('gift retrieved successfully', new GiftResource($gift));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateGiftRequest $request, $gift)
    {
        $gift = Gift::where('id', $gift)->orWhere('slug', $gift)->firstOrFail();

        $gift->update($request->validated());

        if ($request->has('tags')) {
            $gift->syncTags($request->tags);
        }

        return jsonResponse(true, 'Gift updated successfully', new GiftResource($gift));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($gift)
    {
        $gift = Gift::where('id', $gift)->orWhere('slug', $gift)->firstOrFail();

        $gift->delete();

        return jsonResponse(true, 'Gift deleted successfully', null);
    }
}
