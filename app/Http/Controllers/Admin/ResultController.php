<?php

namespace App\Http\Controllers\Admin;

use App\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ResultController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function overview(Request $request)
    {
        if (!$request->ajax()) {
            return response()->json(['error' => 'invalid connection'], 406);
        }
        // 진행 상태 확인
        $settings = Setting::findOrFail(1, ['state', 'supply', 'capital']);
        if ($settings->state != 'close') {
            return reponse()->json(['error' => '투자가 마감되지 않았습니다.'], 406);
        }

        $users = \App\User::all();
        foreach ($users as $user) {
            if ($user->funds->sum('investment') != $settings->supply) {
                $user->funds()->delete();
            }
        }

        $results = null;
        $total = 0;

        $items = \App\Item::all(['id', 'title']);

        foreach ($items as $index => $item) {
            $results[$index]['id'] = $item->id;
            $results[$index]['title'] = $item->title;
            $results[$index]['coin'] = $item->funds->sum('investment');

            $total += $results[$index]['coin'];
        }

        foreach ($results as $index => $result) {
            $results[$index]['investment'] = round($result['coin'] * $settings->capital / $total);
        }

        return response()->json($results);
    }

    public function detail($id, Request $request)
    {
        if (!$request->ajax()) {
            return response()->json(['error' => 'invalid connection'], 406);
        }
        // 진행 상태 확인
        $settings = Setting::findOrFail(1, ['state', 'supply', 'capital']);
        if ($settings->state != 'close') {
            return reponse()->json(['error' => '투자가 마감되지 않았습니다.'], 406);
        }
        $funds = \App\Fund::where('item_id', $id)->get();

        $results = null;
        $total = 0;

        foreach ($funds as $fund) {
            $results['data'][] = [
                'name' => $fund->user->name,
                'username' => $fund->user->username,
                'investment' => $fund->investment
            ];
            $total += $fund->investment;
        }
        $results['total'] = $total;

        return response()->json($results);
    }
}
