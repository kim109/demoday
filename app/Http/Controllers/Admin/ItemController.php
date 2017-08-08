<?php

namespace App\Http\Controllers\Admin;

use App\Item;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ItemController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function store(Request $request)
    {
        if (!$request->ajax()) {
            return response()->json(['error' => 'invalid connection'], 406);
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

    public function edit($id, Request $request)
    {
        if (!$request->ajax()) {
            return response()->json(['error' => 'invalid connection'], 406);
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

    public function remove($id, Request $request)
    {
        if (!$request->ajax()) {
            return response()->json(['error' => 'invalid connection'], 406);
        }

        $item = Item::find($id);
        $item->delete();

        return response()->json(['result' => 'success']);
    }

}
