<?php

namespace App\Http\Controllers;

use Auth;
use App\Setting;
use App\Item;
use App\Fund;
use Illuminate\Http\Request;

class MainController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        return view('main');
    }

    public function items(Request $request)
    {
        if (!$request->ajax()) {
            return reponse()->json(['error' => 'invalid connection'], 406);
        }

        $user = Auth::user();
        $settting = Setting::find(1, ['supply']);

        $items = Item::all(['id', 'title', 'company', 'speaker', 'description']);
        foreach ($items as $index => $item) {
            $item->description = nl2br($item->description);

            $fund = Fund::where([
                ['user_id', $user->id],
                ['item_id', $item->id]
            ])->first(['investment']);

             $item->investment = ($fund == null) ? 0 : $fund->investment;
        }

        return response()->json(['coin'=>$settting->supply, 'items' => $items]);
    }
}
