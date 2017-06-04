<?php

namespace App\Http\Controllers;

use App\Item;
use App\ItemCase;
use App\ItemCaseLog;
use App\ItemRarity;
use App\ItemUser;
use App\RareAbstract;
use Illuminate\Http\Request;

class CasesController extends Controller
{
    public function index() {
        $cases = ItemCase::with('weapons.rarity')->where('collection', false)->latest()->get();

        return view('cases.index', compact('cases'));
    }

    public function collections() {
        $collections = ItemCase::with('weapons.rarity')->where('collection', true)->get();

        return view('cases.collections', compact('collections'));
    }

    public function show(ItemCase $case) {
        $items = $case->weapons;

        return view('cases.show', compact('case', 'items'));
    }

    public function getRouletteItems(Request $request) {
        $items = ItemCase::where('id', $request->caseid)->first()->items;
        $rarityChances5 = [79.539, 16.424, 3.257, 0.553, 0.227];
//        $rarityChances6 = [79.539, 10.424, 6, 3.257, 0.553, 0.227];
        $rarityChances6 = [79.539, 89.963, 95.963, 99.220, 99.773, 100];

        if(ItemCase::find($request->caseid)->collection) {
            $maxRarity = $items->max('rarity_id');
            $minRarity = $items->min('rarity_id');
            $raritiesAmount = $maxRarity - $minRarity + 1;
            $rarities = ItemRarity::where('id', '<', $maxRarity)->get();
            $rouletteItems = collect([]);
            $baseItem = $items->where('rarity_id', mt_rand($minRarity, $maxRarity))->random();
            for($i = 0; $i < 35; $i++) {
                $randRarity = rand(0, 10000) / 100;
                $raritiesProbability = $this->getRarityProbabilities($raritiesAmount);
                for($j = 0; $j < $raritiesAmount; $j++) {
                    if($randRarity < $raritiesProbability[$j]) {
                        $baseItem = $items->where('rarity_id', ($minRarity + $j))->random();
                        break;
                    }
                }
                //$baseItem = $items->where('rarity_id', mt_rand($minRarity, $maxRarity))->random();
                $randStattrak = (int)(mt_rand(1, 10) == 10);
                if(! ItemCase::find($request->caseid)->souvenir) {
                    $randStattrak = 0;
                }
                $randCondition = $baseItem->conditions[mt_rand(0, count($baseItem->conditions) - 1)];
                $item = $baseItem->childItems->where('souvenir', $randStattrak)->where('condition_id', $randCondition)->first();

                $rouletteItems->prepend($item);
            }
        }
        else {
            $rarities = ItemRarity::where('id', '>', '2')->get();
            $rouletteItems = collect([]);
            for($i = 0; $i < 35; $i++) {
                $randStattrak = (int)(mt_rand(1, 10) == 10);
                $randRarity = rand(0, 10000) / 100;
                if($randRarity < $rarities[0]->drop_sum_rate) {
                    $baseItem = $items->where('rarity_id', $rarities[0]->id)->random();
                }
                elseif($randRarity < $rarities[1]->drop_sum_rate) {
                    $baseItem = $items->where('rarity_id', $rarities[1]->id)->random();
                }
                elseif($randRarity < $rarities[2]->drop_sum_rate) {
                    $baseItem = $items->where('rarity_id', $rarities[2]->id)->random();
                }
                elseif($randRarity < $rarities[3]->drop_sum_rate) {
                    $baseItem = $items->where('rarity_id', $rarities[3]->id)->random();
                }
                else {
                    $baseItem = $items->where('rarity_id', $rarities[4]->id)->random();
                    if($request->caseid == ItemCase::where('title', 'Glove Case')->first()->id)
                        $randStattrak = 0;
                }
                $randCondition = $baseItem->conditions[mt_rand(0, count($baseItem->conditions) - 1)];
                $item = $baseItem->childItems->where('stattrak', $randStattrak)->where('condition_id', $randCondition)->first();

                $rouletteItems->prepend($item);
            }
        }

        $wonIndex = mt_rand(28, 31);
        auth()->user()->items()->attach($rouletteItems[$wonIndex]->id);
//        ItemUser::create(['user_id' => auth()->user()->id, 'item_id' => $rouletteItems[26]->id, 'item_type' => 'weapon']);
        $rouletteItemsJS = [];
        $count = 0;
        foreach($rouletteItems as $rouletteItem) {
            $rouletteItemsJS[$count]['id'] = $rouletteItem->id;
            $rouletteItemsJS[$count]['blockid'] = 1;
            $rouletteItemsJS[$count]['image'] = $rouletteItem->image;
            $rouletteItemsJS[$count]['rarity'] = strtolower($rouletteItem->baseItem->rarity->title);
            $rouletteItemsJS[$count]['weaponName'] = $rouletteItem->baseItem->WeaponName->title;
            $rouletteItemsJS[$count]['patternName'] = $rouletteItem->baseItem->WeaponPattern->title;
            $rouletteItemsJS[$count]['price'] = $rouletteItem->price;
            $rouletteItemsJS[$count]['condition'] = $rouletteItem->condition->title;
            $rouletteItemsJS[$count]['stattrak'] = $rouletteItem->stattrak;
            $rouletteItemsJS[$count]['souvenir'] = $rouletteItem->souvenir;
            $count++;
        }
        $this->makeCaseLog($request->caseid, $rouletteItems[$wonIndex]->id);

        $ajaxData = [$rouletteItemsJS, $wonIndex];
        return response()->json($ajaxData);
    }

    public function shop(ItemCase $case) {
//        $items = $case->iitems()->orderBy('rarity_id', 'desc')->get();
        /*
        $items = $case->weapons()
            ->join('items', 'items.item_abstract_id', '=', 'items_abstract.id')
            ->join('items_rarities', 'items_rarities.id', '=', 'items_abstract.rarity_id')
            ->orderBy('items_abstract.rarity_id', 'desc')
            ->orderBy('items_abstract.item_name_id')
            ->orderBy('stattrak', 'desc')
            ->orderBy('condition_id', 'asc')
//            ->groupBy('items_abstract.rarity_id')
            ->get();
//        dd($items);
        */
        $itemsAbstract = $case->weapons()->pluck('items_abstract.id')->toArray();
        $items = Item::whereIn('item_abstract_id', $itemsAbstract)
            ->select('items.*', 'items_abstract.rarity_id', 'items_abstract.id AS item_abstract_id')
            ->join('items_abstract', 'items_abstract.id', '=', 'items.item_abstract_id')
//            ->join('items_rarities', 'items_rarities.id', '=', 'items_abstract.rarity_id')
            ->orderBy('items_abstract.rarity_id', 'desc')
            ->orderBy('items_abstract.item_name_id')
            ->orderBy('stattrak', 'desc')
            ->orderBy('condition_id', 'asc')
            ->get();
        $itemsGroups = $items->groupBy('item_abstract_id');
//        dd($items);
        return view('cases.shop', compact('case', 'itemsGroups'));
    }

    public function buyShopItem(Request $request) {
        $item = Item::find($request->itemid);
        $user = auth()->user();
        $user->items()->attach($item->id);
        $user->points -= ceil($item->price);
        $user->save();

        return response()->json('success', 200);
    }

    public function makeCaseLog($caseId, $itemId) {
        ItemCaseLog::create(['case_id' => $caseId, 'item_id' => $itemId, 'user_id' => auth()->user()->id]);
    }

    public function logs() {
        $logs = auth()->user()->casesLogs()->paginate(20);
        return view('cases.logs', compact('logs'));
    }

    private function getRarityProbabilities($raritiesAmount) {
        $raritiesProbability = [];
        switch($raritiesAmount) {
            case 3: $raritiesProbability = [75, 95, 100]; break;
            case 4: $raritiesProbability = [70, 85, 95, 100]; break;
            case 5: $raritiesProbability = [70, 80, 90, 96, 100]; break;
            case 6: $raritiesProbability = [70, 80, 90, 96, 99, 100]; break;
            default: $raritiesProbability = [];
        }
        return $raritiesProbability;
    }
}