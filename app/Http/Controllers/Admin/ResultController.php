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
            return response()->json(['errors' => 'invalid connection'], 406);
        }
        // 진행 상태 확인
        $settings = Setting::findOrFail(1);
        if ($settings->status != 'close') {
            return reponse()->json(['errors' => '투자가 마감되지 않았습니다.'], 406);
        }

        $users = \App\User::all();
        foreach ($users as $user) {
            $coin = (int)$settings->supply;
            foreach ($settings->experts as $expert) {
                if ($expert['username'] == $user->username) {
                    $coin = $coin * $setting->multiple;
                }
            }
            if ($user->funds->sum('investment') != $coin) {
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
            return response()->json(['errors' => 'invalid connection'], 406);
        }
        // 진행 상태 확인
        $settings = Setting::findOrFail(1, ['status', 'supply', 'capital']);
        if ($settings->status != 'close') {
            return reponse()->json(['errors' => '투자가 마감되지 않았습니다.'], 406);
        }

        $results = null;
        $funds = \App\Fund::where('item_id', $id)->get();
        foreach ($funds as $fund) {
            $results[] = [
                'name' => $fund->user->name,
                'username' => $fund->user->username,
                'investment' => $fund->investment
            ];
        }

        return response()->json($results);
    }
}
