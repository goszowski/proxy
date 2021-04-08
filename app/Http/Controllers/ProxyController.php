<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Ixudra\Curl\Facades\Curl;
use Illuminate\Support\Arr;
use Log;

class ProxyController extends Controller
{
    public function handle(Request $request)
    {
        $query = Arr::query($request->query());

        $response = Curl::to(config('proxy.url') . $request->path() . ($query ? '?'.$query : null))
            ->withData($request->getContent())
            ->withResponseHeaders()
            ->returnResponseObject()
            ->{$request->method()}();
        
        Log::info(json_encode($response));

        return response($response->content, $response->status, $response->headers);
    }
}
