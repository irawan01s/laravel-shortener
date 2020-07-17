<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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

        $regexp = "/^[0-9a-zA-Z_]{6}$/";
        $shortCode = Str::random(6);
        // $shortCode = 'aHm6BO';

        // if (preg_match($regexp, $shortCode))
        // {
        //     $res = json_encode(['shortcode' => $shortCode]);
        //     $rescode = 201;
        // } else {
        //     $msg = 'The shortcode fails to meet the following regexp: ^[0-9a-zA-Z_]{4,}$.';
        //     $res = json_encode(['error' => $msg]);
        //     $rescode = 422;
        // }

        // $input['link'] = $request->link;
        // $input['code'] = $shortCode;
        if ($this->cekLink($shortCode)) {
            
        }
        // return $res;
        // ShortLink::create($input);
        // return response($res, $rescode)->header('Content-Type', 'application/json');
        // return redirect('/')->with('success', 'Shorten Link Generated Successfully!');
    }

    public function shortenLink($code)
    {
        $find = ShortLink::where('code', $code)->first();
        // dd($find);
        return redirect($find->link);
    }

    public function linkJson($code)
    {
        $find = ShortLink::where('code', $code)->first();
        $res = json_encode(['shortcode' => $find->code]);
        
        return response($res,201)->header('Content-Type', 'application/json');
    }

    public function cekLink($shortLink)
    {
        $cekCode = ShortLink::where('code', $shortLink)->first();
        // dd($cekCode);
        return isset($cekCode);

        // if (@$cekCode->code == $shortLink) {
        //     return 'Ada '.$shortLink;
        // } else {
        //     return 'Tidak Ada '.$shortLink;
        // }
        // return @$cekCode->code == $shortLink;
        // dd($cekCode);
    }
}