<?php

namespace App\Http\Controllers\Admin;

use App\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Events\InvestClosed;

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
        if (!in_array($settings->status, ['close', 'result'])) {
            return reponse()->json(['errors' => '투자가 마감되지 않았습니다.'], 406);
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

        // Pusher
        event(new InvestClosed());

        $settings->status = 'result';
        $settings->save();

        return response()->json($results);
    }

    public function detail($id, Request $request)
    {
        if (!$request->ajax()) {
            return response()->json(['errors' => 'invalid connection'], 406);
        }
        // 진행 상태 확인
        $settings = Setting::findOrFail(1, ['status', 'experts']);
        if ($settings->status != 'result') {
            return response()->json(['errors' => '투자가 마감되지 않았습니다.'], 406);
        }

        $experts = [];
        foreach ($settings->experts as $expert) {
            $experts[] = $expert['username'];
        }

        $results = null;
        $funds = \App\Fund::where('item_id', $id)->get();
        foreach ($funds as $fund) {
            if (!in_array($fund->user->username, $experts)) {
                $results[] = [
                    'name' => $fund->user->name,
                    'username' => $fund->user->username,
                    'investment' => (int)$fund->investment
                ];
            }
        }

        return response()->json($results);
    }
}
