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
        $settings = Setting::find(1)
                    ->first(['state', 'supply', 'capital']);

        return response()->json($settings);
    }
}
