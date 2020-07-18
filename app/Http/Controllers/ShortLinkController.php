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

        return view('shortLink', compact('shortLinks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'link' => 'required|url'
        ]);

        $regexp    = "/^[0-9a-zA-Z_]{6}$/";
        $shortCode = Str::random(6);
        // $shortCode = 'utoVT';

        $res = json_encode(['shortcode' => $shortCode]);
        $resCode = 201;
        
        $existCode = ShortLink::where('shortcode', $shortCode)->count();
        $reqUrl = Http::get($request->link)->ok();

        // dd($reqUrl);
        // dd($existCode);

        if (!$reqUrl) {
            $msg = 'url is not present';
            $res = json_encode(['error' => $msg]);
            $resCode = 400;       
        } else if ($existCode >= 1) {
            $msg = 'The desired shortcode is already in use. Shortcodes are case-sensitive.';
            $res = json_encode(['error' => $msg]);
            $resCode = 409;
        } else if (!preg_match($regexp, $shortCode)) {
            $msg = 'The shortcode fails to meet the following regexp: ^[0-9a-zA-Z_]{4,}$.';
            $res = json_encode(['error' => $msg]);
            $resCode = 422;
        } else {
            $input['url']       = $request->link;
            $input['shortcode'] = $shortCode;    
            ShortLink::create($input);

            $res = json_encode(['shortcode' => $shortCode]);
            $resCode = 201;
        }


        return response($res, $resCode)->header('Content-Type', 'application/json');
        // return redirect('/')->with('success', 'Shorten Link Generated Successfully!');
    }

    public function shortenLink($code)
    {
        $find = ShortLink::where('shortcode', $code)->first();

        return redirect($find->url);
    }

    public function destroy($id) {
        $link = ShortLink::find($id);
        $link->delete();

        return redirect('/')->with('success', 'Data berhasil dihapus!');
    }
}