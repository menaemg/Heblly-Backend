<?php

namespace App\Http\Controllers\Api;

use App\Models\Gratitude;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\GratitudeResource;
use App\Http\Requests\Gratitude\StoreGratitudeRequest;
use App\Http\Requests\Gratitude\UpdateGratitudeRequest;

class GratitudeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $gratitudes = Auth::user()->gratitudes()->with('tags')->latest()->paginate(10);

        return jsonResponse(true, 'Gratitude retrieved successfully', GratitudeResource::collection($gratitudes));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreGratitudeRequest $request)
    {
        $user = auth()->user();

        $gratitude = $user->gratitudes()->where('gift_id', $request->gift_id)->first();

        if( $gratitude ) {
            return jsonResponse(false, 'Gratitude already exists');
        }

        $gratitude = $user->gratitudes()->create($request->validated());


        $gratitude->attachTags($request->tags);

        return jsonResponse(true,'Gratitude created successfully' , new GratitudeResource($gratitude));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($gratitude)
    {
        $gratitude = Gratitude::where('id', $gratitude)->orWhere('slug', $gratitude)->firstOrFail();

        return jsonResponse('gratitude retrieved successfully', new GratitudeResource($gratitude));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateGratitudeRequest $request, $gratitude)
    {
        $gratitude = Gratitude::where('id', $gratitude)->orWhere('slug', $gratitude)->firstOrFail();

        $gratitude->update($request->validated());

        if ($request->has('tags')) {
            $gratitude->syncTags($request->tags);
        }

        return jsonResponse(true, 'Gratitude updated successfully', new GratitudeResource($gratitude));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($gratitude)
    {
        $gratitude = Gratitude::where('id', $gratitude)->orWhere('slug', $gratitude)->firstOrFail();

        $gratitude->delete();

        return jsonResponse(true, 'Gratitude deleted successfully', null);
    }
}
