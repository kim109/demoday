<?php

namespace App\Http\Controllers\Admin;

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
        $settings = Setting::all();
        $items = Item::all();

        return view('admin.index', ['settings' => $settings]);
    }

    public function searchAD(Request $request)
    {
        if (!$request->ajax()) {
            return response()->json(['error' => 'invalid connection'], 406);
        }
        $this->validate($request, [
            'q' => 'required'
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
            return response()->json(['error' => 'invalid connection'], 406);
        }
        $settings = Setting::findOrFail(1, ['state', 'supply', 'capital', 'experts', 'multiple']);
        $repsonse = $settings->toArray();
        $items = Item::all();
        $repsonse['items'] = $items->toArray();

        return response()->json($repsonse);
    }

    public function setSetting(Request $request)
    {
        if (!$request->ajax()) {
            return response()->json(['error' => 'invalid connection'], 406);
        }
        $this->validate($request, [
            'supply' => 'integer|min:1',
            'capital' => 'integer|min:1',
            'multiple' => 'integer|min:1',
            'state' => 'in:ready,open,close'
        ]);

        $setting = Setting::find(1);

        if ($request->has('supply')) {
            $setting->supply = $request->input('supply');
        }
        if ($request->has('capital')) {
            $setting->capital = $request->input('capital');
        }
        if ($request->has('experts')) {
            $setting->experts = $request->input('experts');
        }
        if ($request->has('multiple')) {
            $setting->multiple = $request->input('multiple');
        }
        if ($request->has('state')) {
            $setting->state = $request->input('state');
        }

        $setting->save();

        return response()->json(['result' => 'success']);
    }

    public function reset(Request $request)
    {
        $setting = Setting::find(1);
        $setting->state = 'ready';
        $setting->supply = 100;
        $setting->capital = 10000000;
        $setting->experts = null;
        $setting->multiple = null;
        $setting->save();

        Item::truncate();
        \App\Fund::truncate();

        return response()->json(['result' => 'success']);
    }
}
