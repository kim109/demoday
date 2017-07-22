<?php

namespace App\Http\Controllers;

use App\Setting;
use App\Item;
use Illuminate\Http\Request;

class MainController extends Controller
{
    public function index(Request $request)
    {
        return view('main');
    }

    public function items(Request $request)
    {
        if (!$request->ajax()) {
            return reponse()->json(['error' => 'invalid connection'], 406);
        }

        $settting = Setting::find(1, ['supply']);

        $items = Item::all(['title', 'company', 'speaker', 'description']);


        return response()->json(['coin'=>$settting->supply, 'items' => $items]);
    }
}
