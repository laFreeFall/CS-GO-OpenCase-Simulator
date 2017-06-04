<?php

namespace App\Http\Controllers;

use App\Item;
use App\ItemAbstract;
use App\ItemCase;
use App\ItemPattern;
use App\ItemUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UsersController extends Controller
{
    public function index() {

    }

    public function inventory() {
//        $items = auth()->user()->items()->orderBy('price', 'desc')->paginate(28);
/*
        $items = DB::table('item_user')
            ->join('items', 'item_user.item_id', 'items.id')
            ->join('items_abstract', 'items.item_abstract_id', 'items_abstract.id')
            ->select('item_user.*', 'items.*', 'items_abstract.rarity_id')
            ->where('item_user.user_id', auth()->user()->id)
            ->orderBy('items_abstract.rarity_id', 'desc')
            ->orderBy('items.price', 'desc')
            ->get();
*/
//        $items = auth()->user()->items()->with('baseItem')->orderBy('rarity_id')->orderBy('price')->get();
//        $items = auth()->user()->items()->with('baseItem')->orderBy('items_abstract.rarity_id', 'desc')->orderBy('price', 'desc')->paginate(28);
//        dd(auth()->user()->rares);
//        $rares = auth()->user()->rares()
//            ->join('rares_abstract', 'rares_abstract.id', '=', 'rares.rare_abstract_id')
//            ->orderBy('price', 'desc')
//            ->paginate(30);
        $items = auth()->user()->items()
//            ->select('*')
//            ->select('*', 'count(*) as item_amount')
//            ->join('items_abstract', 'items_abstract.id', '=', 'items.item_abstract_id')
                ->selectRaw('items.*, count(*) as items_count')
            ->join('items_abstract', 'items_abstract.id', '=', 'items.item_abstract_id')
//            ->join('rares_abstract', 'rares_abstract.id', '=', 'rares.rares_abstract_id')
            ->orderBy('rarity_id', 'desc')
            ->orderBy('price', 'desc')
            ->groupBy('items.id')
//            ->get();
            ->paginate(30);
//        dd($items);


//        dd($items);
        return view('users.inventory', compact('items'));
    }

    public function sellItem(Request $request) {
        $item = Item::find($request->itemid);
        $user = auth()->user();
//        $firstLink = ItemUser::where(['user_id' => $user->id, 'item_id' => $item->id])->first();
//        $user->items()->newPivotStatementForId($item->id)->where('id', $firstLink->id)->delete();
        ItemUser::where(['user_id' => $user->id, 'item_id' => $item->id])->first()->delete();
        if($item->price > 1) {
            $user->points += floor($item->price);
            $user->save();
        }

        return response()->json(['profit' => floor($item->price)]);
    }

    public function stats() {
        $user = auth()->user();
        $stats = [];
        $stats['cases']['totalCasesOpened'] = $user->casesLogs->count();
//        $stats['cases']['totalMoneyWasted'] = $user->casesLogs->join('')count();
        return view('users.stats', compact('user', 'stats'));
    }

    public function collector() {
        $user = auth()->user();
        $cases = ItemCase::with('coverts')->where('collection', false)->get();
//        $knivesGroupped = ItemAbstract::where('rarity_id', 7)->get();
        $knivesGroupped = Item::join('items_abstract', 'items_abstract.id', '=', 'items.item_abstract_id')
            ->join('items_names', 'items_abstract.item_name_id', 'items_names.id')
            ->where('items_names.item_type_id', 5)
            ->where('items.condition_id', 1)
            ->orderBy('items.price', 'desc')
            ->get();
        $knivez = $knivesGroupped->where('item_pattern_id', ItemPattern::where('title', 'Vanilla')->first()->id)->where('stattrak', 0)->sort()->values();
        $knives = collect([]);
        foreach($knivez as $knife) {
            $knives->push($knife);
        }
        $knivesGroupped = $knivesGroupped->groupBy('title')->sort()->values();
//        $knivesGroupped = $knivesGroupped->groupBy('item_name_id');
        return view('users.collector', compact('user', 'cases', 'knives', 'knivesGroupped'));
    }
}