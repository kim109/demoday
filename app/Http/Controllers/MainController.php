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

    public function investment(Request $request)
    {
        if (!$request->ajax()) {
            return reponse()->json(['error' => 'invalid connection'], 406);
        }

        $this->validate($request, [
            'item' => 'required|integer',
            'investment' => 'required|integer|min:1|max:99'
        ], [
            'investment.required' => '투자금을 입력하세요.',
            'investment.integer' => '투자금은 숫자로 입력하세요.',
            'investment.min' => '투자금은 최소 1 이상으로 입력하세요.',
            'investment.max' => '투자금은 최소 99 이하로 입력하세요.'
        ]);

        $user = Auth::user();

        $settting = Setting::find(1, ['supply']);
        $consume = Fund::where([
            ['user_id', $user->id],
            ['item_id', '!=', $request->input('item')]
        ])->sum('investment');

        if ($settting->supply < $consume + $request->input('investment')) {
            return response()->json(['errors'=> ['총 투자액 이상으로 투자하실 수 없습니다.']], 422);
        }

        Fund::updateOrCreate(
            ['user_id' => $user->id, 'item_id' => $request->input('item')],
            ['investment' => $request->input('investment')]
        );

        return response()->json(['coin'=>1]);
    }
}
