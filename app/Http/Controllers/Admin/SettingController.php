<?php

namespace App\Http\Controllers\Admin;

use Redis;
use App\Setting;
use App\Item;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SettingController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function index(Request $request)
    {
        return view('admin.index');
    }

    public function searchAD(Request $request)
    {
        if (!$request->ajax()) {
            return response()->json(['errors' => 'invalid connection'], 406);
        }
        $this->validate($request, [
            'q' => 'required'
        ], [], [
            'q' => '검색어'
        ]);

        $keyword = $request->input('q');
        $users = \Adldap::search()->select('cn', 'displayName')
                ->orwhereContains('cn', $keyword)
                ->orWhereContains('displayName', $keyword)
                ->get();

        $items = [];
        foreach ($users as $user) {
            $items[] = array(
                'label' => $user->displayname[0].'('.$user->cn[0].')',
                'username' => $user->cn[0],
                'name' => $user->displayname[0]
            );
        }

        return response()->json(['items' => $items]);
    }

    public function getSetting(Request $request)
    {
        if (!$request->ajax()) {
            return response()->json(['errors' => 'invalid connection'], 406);
        }
        $settings = Setting::findOrFail(1, ['status', 'supply', 'capital', 'experts', 'ratio']);
        if ($settings->status == 'result') {
            $settings->status = 'close';
        }
        $repsonse = $settings->toArray();
        $items = Item::all();
        $repsonse['items'] = $items->toArray();

        return response()->json($repsonse);
    }

    public function setSetting(Request $request)
    {
        if (!$request->ajax()) {
            return response()->json(['errors' => 'invalid connection'], 406);
        }
        $this->validate($request, [
            'supply' => 'integer|min:1',
            'capital' => 'integer|min:1',
            'ratio' => 'integer|min:1|max:99',
            'status' => 'in:ready,open,close'
        ], [], [
            'supply' => '개인별 지급 J-Coin',
            'capital' => '실제 투자액',
            'ratio' => '전문가 전체 투자금 할당 비율'
        ]);

        $setting = Setting::find(1);

        if ($request->has('supply') && $setting->status == 'ready') {
            $setting->supply = $request->input('supply');
        }
        if ($request->has('capital') && $setting->status == 'ready') {
            $setting->capital = $request->input('capital');
        }
        if ($request->has('experts') && $setting->status == 'ready') {
            $setting->experts = $request->input('experts');
        }
        if ($request->has('ratio') && $setting->status == 'ready') {
            $setting->ratio = $request->input('ratio');
        }
        if ($request->has('status')) {
            if ($request->input('status') == 'close') {
                $users = \App\User::all();
                $coin = $setting->supply;
                $count = Item::count();
                foreach ($users as $user) {
                    if ($user->funds->sum('investment') != $coin || $user->funds->count() != $count) {
                        $user->funds()->delete();
                    }
                }
            }
            $setting->status = $request->input('status');
        }

        $setting->save();

        return response()->json(['result' => 'success']);
    }

    public function reset(Request $request)
    {
        // 설정 상태 준비중으로
        $setting = Setting::find(1);
        $setting->status = 'ready';
        $setting->save();

        // 이벤트 내용 삭제
        Item::query()->update([
            'event_open' => false,
            'event_rank' => null,
            'event_winner' => null
        ]);
        Redis::command('flushdb');

        // 투자금 내역 삭제
        \App\Fund::truncate();

        return response()->json(['result' => 'success']);
    }
}
