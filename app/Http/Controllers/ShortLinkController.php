<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
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

        $input['link'] = $request->link;
        $input['code'] = Str::random(6);

        ShortLink::create($input);

        return redirect('generate-shorten')->with('success', 'Shorten Link Generated Successfully!');
    }

    public function shortenLink($code)
    {
        $find = ShortLink::where('code', $code)->first();
        dd($find);
        return redirect($find->link);
    }

    public function linkJson($code)
    {
        $find = ShortLink::where('code', $code)->first();
        $res = json_encode(['shortcode' => $find->code]);
         return response($res,201)->header('Content-Type', 'application/json');
    }
}
