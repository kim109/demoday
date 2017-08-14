<?php

namespace App\Http\Controllers\Admin;

use Redis;
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
            return response()->json(['errors' => 'invalid connection'], 406);
        }
        $this->validate($request, [
            'title' => 'required',
            'company' => 'required',
            'speaker' => 'required',
            'description' => 'required',
        ], [], [
            'title' => '발표 제목',
            'company' => '발표자 회사',
            'speaker' => '발표자 이름',
            'description' => '발표 내용 요약'
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
            return response()->json(['errors' => 'invalid connection'], 406);
        }
        $this->validate($request, [
            'title' => 'required',
            'company' => 'required',
            'speaker' => 'required',
            'description' => 'required',
        ], [], [
            'title' => '발표 제목',
            'company' => '발표자 회사',
            'speaker' => '발표자 이름',
            'description' => '발표 내용 요약'
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
            return response()->json(['errors' => 'invalid connection'], 406);
        }

        $item = Item::find($id);
        $item->delete();

        return response()->json(['result' => 'success']);
    }

    public function event($id, Request $request)
    {
        if (!$request->ajax()) {
            return response()->json(['errors' => 'invalid connection'], 406);
        }
        $this->validate($request, [
            'open' => 'required|boolean',
            'rank' => 'integer|min:1|max:300'
        ], [], [
            'rank' => '당첨 순번'
        ]);

        $item = Item::find($id);
        $key = 'Item:'.$id.':Event';
        $response = ['result' => 'success'];

        $open = $request->input('open');
        if ($open) {
            $item->event_open = true;
            $item->event_rank = $request->input('rank');
            $item->event_winner = null;
            $item->save();

            Redis::command('del', [$key]);
        } else {
            $rank = $item->event_rank;
            $result = Redis::command('ZRANGE', [$key, $rank-1, $rank-1]);
            $user = empty($result) ? null : User::find($result[0]);

            if ($user == null) {
                $winner = null;
            } else {
                $winner = $user->name.'('.$user->username.')';
            }

            $item->event_open = false;
            $item->event_winner = $winner;
            $item->save();

            $response['winner'] = $winner;
        }

        return response()->json($response);
    }
}
