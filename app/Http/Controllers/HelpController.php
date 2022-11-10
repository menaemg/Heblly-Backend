<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Help\StoreRequest;

class HelpController extends Controller
{
    public function storeRequest(StoreRequest $request)
    {
        $help = auth()->user()->helps()->create($request->validated()+ ['type' => 'request']);

        return jsonResponse(true, "Request Created", [
            'id' => $help->id,
            'subject' => $help->subject,
            'body' => $help->body,
            'type' => $help->type,
            'category' => $help->category,
        ]);
    }

    public function storeReport(StoreRequest $request)
    {
        $help = auth()->user()->helps()->create($request->validated()+ ['type' => 'report']);

        return jsonResponse(true, "Report Created", [
            'id' => $help->id,
            'subject' => $help->subject,
            'body' => $help->body,
            'type' => $help->type,
            'category' => $help->category,
        ]);
    }
}
