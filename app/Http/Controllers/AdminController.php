<?php

namespace App\Http\Controllers;

use App\Setting;
use App\Item;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        $settings = Setting::all();
        $items = Item::all();

        return view('admin.index', ['settings' => $settings]);
    }

    public function getSetting(Request $request)
    {
        $settings = Setting::find(1, ['state', 'supply', 'capital']);

        return response()->json($settings);
    }

    public function setSetting(Request $request)
    {
        $this->validate($request, [
            'supply' => 'integer|min:1',
            'capital' => 'integer|min:1'
        ]);

        $setting = Setting::find(1);

        if ($request->has('supply')) {
            $setting->supply = $request->input('supply');
        }
        if ($request->has('capital')) {
            $setting->capital = $request->input('capital');
        }

        $setting->save();

        return response()->json(['result' => 'success']);
    }
}
