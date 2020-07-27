<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use App\ShortLink;


class ShortLinkController extends Controller
{
    public function index()
    {
        $shortLinks = ShortLink::latest()->get();

        return view('short_link', compact('shortLinks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'link' => 'required|url'
        ]);

        $regexp    = "/^[0-9a-zA-Z_]{6}$/";
        $shortCode = Str::random(6);
        // $shortCode = 'utoVT';
        
        $existCode = ShortLink::where('shortcode', $shortCode)->count();
        $reqUrl = Http::timeout(15)->get($request->link);

        // dd($reqUrl);
        // dd($existCode);

        if (!$reqUrl) {
            $msg = 'url is not present';
            $res = ['error' => $msg];
            $resCode = 400;       
        } else if ($existCode >= 1) {
            $msg = 'The desired shortcode is already in use. Shortcodes are case-sensitive.';
            $res = ['error' => $msg];
            $resCode = 409;
        } else if (!preg_match($regexp, $shortCode)) {
            $msg = 'The shortcode fails to meet the following regexp: ^[0-9a-zA-Z_]{4,}$.';
            $res = ['error' => $msg];
            $resCode = 422;
        } else {
            $input['shortcode']      = $shortCode;
            $input['url']            = $request->link;
            $input['redirect_count'] = 1;
            ShortLink::create($input);

            $res = ['shortcode' => $shortCode];
            $resCode = 201;
        }

        return response()->json($res, $resCode)->header('Content-Type', 'application/json');
        // return redirect('/')->with('success', 'Shorten Link Generated Successfully!');
    }

    public function show($code)
    {
        $find = ShortLink::where('shortcode', $code)->first();
        if ($find) {
            $find->redirect_count += 1;
            $find->save();

            return redirect($find->url);
        } else {
            $msg = 'The shortcode cannot be found in the system.';
            $res = ['error' => $msg];
            $resCode = 404;

            return response()->json($res, $resCode)->header('Content-Type', 'application/json');
        }
    }

    public function stats($code)
    {
        $find = ShortLink::where('shortcode', $code)->first();
        if ($find) {
            $msg = 'The shortcode cannot be found in the system.';
            $res = ['startDate' => $find->created_at->toIso8601ZuluString(), 'lastSeenDate' => $find->updated_at->toDateTimeString(), 'redirectCount' => $find->redirect_count];
            $resCode = 200;

        } else {
            $msg = 'The shortcode cannot be found in the system.';
            $res = ['error' => $msg];
            $resCode = 404;
        }
        return response()->json($res, $resCode)->header('Content-Type', 'application/json');
    }

    public function destroy($id) {
        $link = ShortLink::find($id);
        $link->delete();

        return redirect('/')->with('success', 'Data berhasil dihapus!');
    }
}