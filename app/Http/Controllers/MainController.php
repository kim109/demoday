<?php

namespace App\Http\Controllers;

use Auth;
use App\Setting;
use App\Item;
use App\Fund;
use Redis;
use Illuminate\Http\Request;

class MainController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'status']);
    }

    public function index(Request $request)
    {
        return view('main');
    }

    public function items(Request $request)
    {
        if (!$request->ajax()) {
            return response()->json(['errors' => 'invalid connection'], 406);
        }

        $user = Auth::user();
        $setting = Setting::find(1, ['status', 'supply']);

        $response = [
            'user' => $user,
            'status' => $setting->status,
            'coin' => (int)$setting->supply,
            'items' => []
        ];

        $items = Item::all(['id', 'title', 'company', 'speaker', 'description']);
        foreach ($items as $item) {
            $item->description = nl2br($item->description);

            $fund = Fund::where([
                ['user_id', $user->id],
                ['item_id', $item->id]
            ])->first(['investment']);

             $item->investment = ($fund == null) ? 0 : (int)$fund->investment;
        }
        $response['items'] = $items;

        return response()->json($response);
    }

    public function investment($id, Request $request)
    {
        if (!$request->ajax()) {
            return response()->json(['errors' => 'invalid connection'], 406);
        }

        $this->validate($request, [
            'investment' => 'required|integer|min:1|max:99'
        ], [
            'investment.required' => '투자금을 입력하세요.',
            'investment.integer' => '투자금은 숫자로 입력하세요.',
            'investment.min' => '투자금은 최소 1 이상으로 입력하세요.',
            'investment.max' => '투자금은 최소 99 이하로 입력하세요.'
        ]);

        $user = Auth::user();

        $setting = Setting::find(1, ['status', 'supply']);
        if ($setting->status != 'open') {
            return response()->json(['errors' => ['모의 투자가 마감 되었습니다.']], 403);
        }

        $coin = (int)$setting->supply;
        $consume = Fund::where([
            ['user_id', $user->id],
            ['item_id', '!=', $id]
        ])->sum('investment');

        if ($coin < $consume + $request->input('investment')) {
            return response()->json(['errors'=> ['총 투자액 이상으로 투자하실 수 없습니다.']], 422);
        }

        Fund::updateOrCreate(
            ['user_id' => $user->id, 'item_id' => $id],
            ['investment' => $request->input('investment')]
        );

        return response()->json(['result' => 'success']);
    }

    public function event($id, Request $request)
    {
        if (!$request->ajax()) {
            return response()->json(['errors' => 'invalid connection'], 406);
        }

        $item = Item::find($id);
        if (!$item->event_open) {
            return response()->json(['errors' => '이벤트가 시작되지 않았습니다.'], 401);
        }

        $user = Auth::user();
        $key = 'Item:'.$id.':Event';
        Redis::command('ZADD', [$key, 'NX', microtime(true), $user->id]);

        return response()->json(['result' => 'success']);
    }

    public function status(Request $request)
    {
        if (!$request->ajax()) {
            return response()->json(['errors' => 'invalid connection'], 406);
        }

        $setting = Setting::find(1, ['status']);

        return response()->json(['status' => $setting->status]);
    }

    public function results(Request $request)
    {
        if (!$request->ajax()) {
            return response()->json(['errors' => 'invalid connection'], 406);
        }
        // 진행 상태 확인
        $settings = Setting::findOrFail(1);
        if ($settings->status != 'result') {
            return reponse()->json(['errors' => '결과를 집계 중입니다.'], 406);
        }

        $total = ['normal' => 0, 'expert' => 0];
        $coin = (int)$settings->supply;
        $experts = [];
        foreach ($settings->experts as $expert) {
            $experts[] = $expert['username'];
        }

        $items = \App\Item::all(['id', 'title']);

        foreach ($items as $item) {
            $index = $item->id;
            $results[$index]['id'] = $item->id;
            $results[$index]['title'] = $item->title;
            $results[$index]['normal'] = 0;
            $results[$index]['expert'] = 0;
        }

        $funds = \App\Fund::all();
        foreach ($funds as $fund) {
            $index = $fund->item_id;
            if (in_array($fund->user->username, $experts)) {
                $results[$index]['expert'] += $fund->investment;
                $total['expert'] += $fund->investment;
            } else {
                $results[$index]['normal'] += $fund->investment;
                $total['normal'] += $fund->investment;
            }
        }

        $results = array_values($results);

        foreach ($results as $index => $result) {
            $investment = 0;

            if ($total['normal'] != 0) {
                $capital = $settings->capital *  (1 - $settings->ratio * 0.01);
                $investment += ($capital *  $results[$index]['normal'] / $total['normal']);
            }

            if ($total['expert'] != 0) {
                $capital = $settings->capital *  ($settings->ratio * 0.01);
                $investment += ($capital *  $results[$index]['expert'] / $total['expert']);
            }

            $results[$index]['investment'] = $investment;
        }

        return response()->json($results);
    }
}
