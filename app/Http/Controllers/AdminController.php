<?php

namespace App\Http\Controllers;

use Redis;
use App\Setting;
use App\Item;
use Illuminate\Http\Request;

class AdminController extends Controller
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

    public function getSetting(Request $request)
    {
        if (!$request->ajax()) {
            return reponse()->json(['error' => 'invalid connection'], 406);
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
            return reponse()->json(['error' => 'invalid connection'], 406);
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
        if ($request->has('multiple')) {
            $setting->multiple = $request->input('multiple');
        }
        if ($request->has('state')) {
            $setting->state = $request->input('state');
        }

        $setting->save();

        return response()->json(['result' => 'success']);
    }

    public function getItem($id, Request $request)
    {
        if (!$request->ajax()) {
            return reponse()->json(['error' => 'invalid connection'], 406);
        }
        $item = Item::find($id, ['title', 'company', 'speaker', 'description']);
        if ($item == null) {
            return response()->json(['error' => 'item not found'], 404);
        }

        return response()->json($item);
    }

    public function storeItem(Request $request)
    {
        if (!$request->ajax()) {
            return reponse()->json(['error' => 'invalid connection'], 406);
        }
        $this->validate($request, [
            'title' => 'required',
            'company' => 'required',
            'speaker' => 'required',
            'description' => 'required',
        ]);

        $item = new Item;
        $item->title = $request->input('title');
        $item->company = $request->input('company');
        $item->speaker = $request->input('speaker');
        $item->description = $request->input('description');
        $item->save();

        return response()->json(['result' => 'success', 'item' => $item], 201);
    }

    public function editItem($id, Request $request)
    {
        if (!$request->ajax()) {
            return reponse()->json(['error' => 'invalid connection'], 406);
        }
        $this->validate($request, [
            'title' => 'required',
            'company' => 'required',
            'speaker' => 'required',
            'description' => 'required',
        ]);

        $item = Item::find($id);
        $item->title = $request->input('title');
        $item->company = $request->input('company');
        $item->speaker = $request->input('speaker');
        $item->description = $request->input('description');
        $item->save();

        return response()->json(['result' => 'success']);
    }

    public function removeItem($id, Request $request)
    {
        if (!$request->ajax()) {
            return reponse()->json(['error' => 'invalid connection'], 406);
        }

        $item = Item::find($id);
        $item->delete();

        return response()->json(['result' => 'success']);
    }

    public function event($id, Request $request)
    {
        if (!$request->ajax()) {
            return reponse()->json(['error' => 'invalid connection'], 406);
        }
        $this->validate($request, [
            'open' => 'required|boolean',
            'rank' => 'integer|min:1|max:300'
        ]);

        $item = Item::find($id);

        $open = $request->input('open');
        if ($open) {
            $item->event_open = true;
            $item->event_rank = $request->input('rank');
            $item->event_winner = null;
            $item->save();

            $key = 'Item:'.$id.':Event';
            Redis::command('del', [$key]);
        } else {
            $item->event_open = false;
            $item->save();
        }

        return response()->json(['result' => 'success']);
    }
}
