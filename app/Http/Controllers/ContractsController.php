<?php

namespace App\Http\Controllers;

use App\ContractLog;
use App\Item;
use App\ItemAbstract;
use App\ItemCase;
use App\Weapon;
use App\WeaponAbstract;
use App\ItemUser;
use App\ItemRarity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ContractsController extends Controller
{
    public function index() {
        $rarities = ItemRarity::where('id', '<=', '5')->get();
        return view('contracts.index', compact('rarities'));
    }

    public function show(ItemRarity $rarity, $stattrak = null) {
        $items = auth()->user()->weapons()
            ->selectRaw('items.*, count(*) as items_count')
            ->where('items_abstract.rarity_id', $rarity->id)
            ->where('items.stattrak', $stattrak ? '1' : '0')
            ->where('items.souvenir', 0)
			//->where('items.condition_id', '!=', max('items_abstract.condition_id'))
            ->groupBy('items.id')
            ->get();
//        dd($items->first()->itemCase->first());
        $casesItemsIds = [];
        foreach($items as $item)  {
            $casesItemsIds[] = $item->itemCase->first()->id;
        }
        $casesItemsIds = array_unique($casesItemsIds);
        $nextItemz = [];
        foreach($casesItemsIds as $caseItemId) {
            $itemz = ItemCase::find($caseItemId)->weapons()->where('rarity_id', ($rarity->id + 1))->get();
            $i = 0;
            foreach($itemz as $item) {
                $nextItemz[$caseItemId][$i]['itemid'] = $item->id;
                $nextItemz[$caseItemId][$i]['image'] = $item->image;
                $nextItemz[$caseItemId][$i]['rarity'] = strtolower($item->rarity->title);
                $nextItemz[$caseItemId][$i]['weaponName'] = $item->WeaponName->title;
                $nextItemz[$caseItemId][$i]['weaponPattern'] = $item->WeaponPattern->title;
                $i++;
            }
        }
        $nextItemz = json_encode($nextItemz);
//        $nextItems =

        return view('contracts.show', compact('rarity', 'items', 'nextItemz'));
    }

    public function exchange(Request $request) {
        $user = auth()->user();

        $caseIds = [];
        $items = collect([]);
        $itemsConditions = [];

        foreach($request->items as $itemId) {
            //fix this to make 1 query to DB instead of 10 in this case
            $item = Item::with('baseItem.icase')->find($itemId);
            $items->push($item);
            $itemsConditions[] = $item->condition_id;
            $caseIds[] = $item->baseItem->icase->first()->id;
        }
        $winnableCaseId = $caseIds[mt_rand(0, count($caseIds) - 1)];
        $winnableRarityId = (Item::with('baseItem.rarity')->find($request->items[0])->baseItem->rarity->id) + 1;
        $winnableStattrak = Item::find($request->items[0])->stattrak ? '1' : '0';
//        $winnableItemAbstract = ItemAbstract::where('case_id', $winnableCaseId)->where('rarity_id', $winnableRarityId)->get()->random();
        $winnableItemAbstract = ItemCase::find($winnableCaseId)->weapons()->where('rarity_id', $winnableRarityId)->get()->random();
        //$randCondition = $winnableItemAbstract->conditions[mt_rand(0, count($winnableItemAbstract->conditions) - 1)];
        $randConditions = $this->getRandCondition((array_sum($itemsConditions) / count($itemsConditions)), min($winnableItemAbstract->conditions), max($winnableItemAbstract->conditions));
        $randCondition = mt_rand($randConditions[0], $randConditions[1]);
        $winnableItem = Item::where('item_abstract_id', $winnableItemAbstract->id)
            ->where('condition_id', $randCondition)
            ->where('stattrak', $winnableStattrak)
            ->first();
        $contractLog = ContractLog::create(['user_id' => $user->id, 'received_item_id' => $winnableItem->id]);
        $dataSet = []; $i = 0;
        foreach($request->items as $item) {
            $dataSet[$i]['contract_id'] = $contractLog->id;
            $dataSet[$i]['user_item_id'] = $item;
            $i++;
        }
        DB::table('contract_item')->insert($dataSet);

        //remove from users inventory 10 sent items
        foreach($items as $item) {
            ItemUser::where(['user_id' => $user->id, 'item_id' => $item->id])->first()->delete();
//            $firstLink = ItemUser::where(['user_id' => $user->id, 'item_id' => $item->id, 'item_type' => 'weapon'])->first();
//            $user->items()->newPivotStatementForId($item->id)->where('id', $firstLink->id)->where('item_type', 'weapon')->delete();
        }

        $user->items()->attach($winnableItem);


//        auth()->user()->detach();
        //add to users inventory 1 new item
        $wonItem = [];
        $wonItem['image'] = $winnableItem->image;
        $wonItem['rarity'] = strtolower($winnableItem->baseItem->rarity->title);
        $wonItem['weaponName'] = $winnableItem->baseItem->WeaponName->title;
        $wonItem['patternName'] = $winnableItem->baseItem->WeaponPattern->title;
        $wonItem['price'] = $winnableItem->price;
        $wonItem['condition'] = $winnableItem->condition->title;
        $wonItem['stattrak'] = $winnableItem->stattrak;

        return response()->json($wonItem);

    }

    public function collection(Request $request) {
        $item = Item::find($request->itemid);
        $itemRarity = $item->baseItem->rarity->id + 1;
        $itemCollection = $item->itemCase->first()->id;
        $nextItems = ItemCase::find($itemCollection);
        $nextItems = $nextItems->weapons->where('rarity_id', $itemRarity);
        $nextItemz = []; $i = 0;
        foreach($nextItems as $nextItem) {
            $nextItemz[$i]['image'] = $nextItem->image;
            $nextItemz[$i]['rarity'] = strtolower($nextItem->rarity->title);
            $nextItemz[$i]['weaponName'] = $nextItem->WeaponName->title;
            $nextItemz[$i]['weaponPattern'] = $nextItem->WeaponPattern->title;
            $i++;
        }

        return response()->json($nextItemz);
    }

    public function logs() {
        $items = Item::join('contract_item', 'contract_item.user_item_id', '=', 'items.id')
            ->join('contracts_logs', 'contracts_logs.id', '=', 'contract_item.contract_id')
            ->get();
        $items = $items->groupBy('contract_id');
        $contracts = ContractLog::orderBy('id', 'desc')->paginate(10);
        return view('contracts.logs', compact('contracts', 'items'));
    }

    public function getRandCondition($avg, $bestCondition, $worstCondition) {

        //$bottom = (int)ceil($avg) > $maxCondition ? $maxCondition : (int)ceil($avg);
        $worst = (int)ceil($avg);
		$best = $bestCondition;
		// [3;5], 2
        if($worst < $bestCondition) {
            $best = $worst = $bestCondition;
        }
        if($worst > $worstCondition) {
            $worst = $worstCondition;
        }
        return [$best, $worst];
    }
}