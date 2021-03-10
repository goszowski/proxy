<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Ixudra\Curl\Facades\Curl;
use Illuminate\Support\Arr;

class ProxyController extends Controller
{
    public function handle(Request $request)
    {
        $query = Arr::query($request->query());

        $headers = [];

        foreach($request->header() as $key=>$arr)
        {
            if(isset($arr[0]))
            {
                $headers = array_merge($headers, [$key => $arr[0]]);
            }
        }

        if(isset($headers['host']))
            unset($headers['host']);

        $response = Curl::to(config('proxy.url') . $request->path() . ($query ? '?'.$query : null))
            ->withData($request->getContent())
            ->withHeaders($headers)
            // ->allowRedirect()
            ->returnResponseObject()
            ->{$request->method()}();

        return response($response->content, $response->status)->header('Content-Type', $response->contentType);
    }
}
