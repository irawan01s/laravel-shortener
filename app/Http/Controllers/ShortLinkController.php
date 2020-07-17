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
        // $shortCode = Str::random(6);
        $shortCode = 'utoVTA';

        $res = json_encode(['shortcode' => $shortCode]);
        $rescode = 201;
        
        $existCode = $this->cekCode($shortCode);
        dd($this->cekUrl($request->link));
        // dd($existCode);

        if ($this->cekUrl($request->link) == 400) {
            $msg = 'url is not present';
            $res = json_encode(['error' => $msg]);
            $rescode = 400;       
        } else if ($existCode) {
            $msg = 'The desired shortcode is already in use. Shortcodes are case-sensitive.';
            $res = json_encode(['error' => $msg]);
            $rescode = 409;
        } else if (!preg_match($regexp, $shortCode)) {
            $msg = 'The shortcode fails to meet the following regexp: ^[0-9a-zA-Z_]{4,}$.';
            $res = json_encode(['error' => $msg]);
            $rescode = 422;
        } else {
            $input['url']       = $request->link;
            $input['shortcode'] = $shortCode;
    
            ShortLink::create($input);
        }


        return response($res, $rescode)->header('Content-Type', 'application/json');
        // return redirect('/')->with('success', 'Shorten Link Generated Successfully!');
    }

    public function shortenLink($code)
    {
        $find = ShortLink::where('shortcode', $code)->first();

        return redirect($find->url);
    }
    
    private function cekCode($shortCode)
    {
        $code = ShortLink::where('shortcode', $shortCode)->get();
        
        if (@$cekCode->code == $shortCode) {
            return false;
        } else {
            return true;
        }
    }

    private function cekUrl($url)
    {
        $data = Http::get($url);

        return $data->status();
    }
}