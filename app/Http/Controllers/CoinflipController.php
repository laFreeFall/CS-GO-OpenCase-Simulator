<?php

namespace App\Http\Controllers;

use App\Bot;
use App\CoinflipLog;
use App\Item;
use App\ItemUser;
use App\Weapon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CoinflipController extends Controller
{
    public function index() {
        $items = auth()->user()->items()
            ->selectRaw('items.*, count(*) as items_count')

            ->join('items_abstract', 'items_abstract.id', '=', 'items.item_abstract_id')
            ->orderBy('rarity_id', 'desc')
            ->orderBy('price', 'desc')
            ->groupBy('items.id')

            ->paginate(100);

        return view('coinflip.index', compact('items'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function flip(Request $request) {
        $items = collect([]);
        foreach($request->itemIds as $itemId) {
            //fix this to make 1 query to DB instead of 10 in this case
            $item = Item::with('baseItem.icase')->find($itemId);
//            $flights = App\Flight::find([1, 2, 3]);
            $items->push($item);
        }
        $totalMoney = $items->sum('price');
        $maxItemMoney = Item::max('price');
        $lowestMoney = $totalMoney - $totalMoney * 0.1;
        $highestMoney = $totalMoney + $totalMoney * 0.1;
        $enemyWeapons = collect([]);
        $itemz = Item::whereBetween('price', [$lowestMoney, $highestMoney])->get();
        if($itemz->count() == 0) {
            $totalPrice = $totalMoney;
            for ($i = 0; $i < 10; $i++) {
                if($i = 0) {
                    $bufItem = Item::orderBy('price', 'asc')->first();
                } else {
                    for($j = 0.1; $j < 5.0; $j+=0.1) {
                        $bufItem = Item::whereBetween('price', [$totalPrice - $totalPrice * $j, $totalPrice + $totalPrice * $j])->get();
                        if($bufItem->count() > 0) {
                            $bufItem = $bufItem->random();
                            break;
                        }
                    }
                }
                $enemyWeapons->push($bufItem);
                $totalPrice -= $bufItem->price;
                if($totalPrice < ($totalMoney / 10))
                    break;
            }
        } else {
            $enemyWeapons->push($itemz->random());
        }
        $totalEnemyMoney = $enemyWeapons->sum('price');
        $victory = (int)(mt_rand(0, ($totalMoney * 100) + ($totalEnemyMoney * 100)) < ($totalMoney * 100));
        $tSide = ($request->tSide === 'true') ? 1 : 0;
        $dopAngle = ($victory == $tSide) ? 1 : 0;
        $angle = 180 * (10 + $dopAngle);
        if($victory) {
            foreach($enemyWeapons as $enemyWeapon) {
                auth()->user()->items()->attach($enemyWeapon->id);
            }
        }
        else {
            foreach($items as $item) {
                ItemUser::where(['user_id' => auth()->user()->id, 'item_id' => $item->id])->first()->delete();
            }
        }
        $wonItems = [];
        $i = 0;
        foreach($enemyWeapons as $enemyWeapon) {
            $wonItems[$i]['image'] = $enemyWeapon->image;
            $wonItems[$i]['rarity'] = strtolower($enemyWeapon->baseItem->rarity->title);
            $wonItems[$i]['title'] = $enemyWeapon->middle_title;
            $wonItems[$i]['price'] = $enemyWeapon->price;
            $wonItems[$i]['condition'] = $enemyWeapon->condition->title;
            $wonItems[$i]['stattrak'] = $enemyWeapon->stattrak;
            $i++;
        }


//        $wonItems = json_encode($wonItems);
        $enemyRand = Bot::all()->random();
        $coinflipLog = CoinflipLog::create(['user_id' => auth()->user()->id, 'enemy_id' => $enemyRand->id, 'side' => $tSide, 'victory' => $victory]);

        $dataSet = []; $i = 0;
        foreach($request->itemIds as $itemId) {
            $dataSet[$i]['coinflip_id'] = $coinflipLog->id;
            $dataSet[$i]['item_id'] = $itemId;
            $dataSet[$i]['user_item'] = true;
            $i++;
        }
        DB::table('coinflip_item')->insert($dataSet);

        $dataSet = []; $i = 0;
        foreach($enemyWeapons as $enemyWeapon) {
            $dataSet[$i]['coinflip_id'] = $coinflipLog->id;
            $dataSet[$i]['item_id'] = $enemyWeapon->id;
            $dataSet[$i]['user_item'] = false;
            $i++;
        }
        DB::table('coinflip_item')->insert($dataSet);

        $enemy = ['username' => $enemyRand->username, 'avatar' => $enemyRand->avatar];
        $data = [$wonItems, $victory, $angle, $enemy, $tSide];
        return response()->json($data);
    }

    public function logs() {
        $items = Item::join('coinflip_item', 'coinflip_item.item_id', '=', 'items.id')
            ->join('coinflip_logs', 'coinflip_logs.id', '=', 'coinflip_item.coinflip_id')
            ->get();
        $items = $items->groupBy('coinflip_id');
        $coinflips = CoinflipLog::orderBy('id', 'desc')->paginate(10);
        return view('coinflip.logs', compact('coinflips', 'items'));
    }

//    private function getRandomImage($path) {
//        $dir = 'storage/images/' . $path . '/';
//        $images = scandir($dir);
//        $i = rand(2, sizeof($images)-1);
//        return $images[$i];
//    }
//
//    public function getRandomUsername() {
//        $usernames = ['Sgt. Traveler', 'The_Other_Retard', 'CandyRaid', '0Zero0', '15avigil', '447u', '~sound~wave~', 'AirFusion', 'AmazingHuh', 'aranamor', 'atomic7732', 'Awesomus Maximus', 'Billybobjoepants', 'BioShock_Rules', 'Blanzer', 'Bob-Omb', 'bosky2102', 'Brendan170', 'broswen', 'Btrpo', 'bubbyboytoo', 'CandyRaid', 'Carmelpoptart', 'catlover2011', 'cejj99', 'Chan14551', 'ChowderBowl', 'Chzydeath', 'CoolBlueJ', 'cupcakes_rock', 'DaBomb', 'Darvince', 'DaSnipeKid', 'Dawnofdusk', 'De_n00bWOLF', 'DeepDarkSamurai', 'DefaultAsAwesome', 'diamondhand146', 'DirtDog', 'DivinityV2', 'DontStealMyBacon', 'Draconus', 'Dylanf3', 'Em0srawk', 'enderfemale', 'Entophobia', 'Erak606', 'F0R1', 'faloxx', 'fire3232', 'firehawk729', 'Firestix', 'Fixin', 'For_the_lolz', 'FoxHound42', 'Gail102', 'GamingChanging', 'geez', 'georgeyves', 'goodatthis123', 'grox19', 'haltyoudoglovers', 'Hideki Ryuga', 'iiTzWho', 'ii{i(DELTA)i}ii', 'IkubiAkius', 'ize]KuruKuruGuy', 'JaffaHunter', 'Jax4321', 'JesusoChristo', 'JRyno', 'KillerPlant', 'KyoDemer', 'Leostereo', 'LMMN', 'LokiDarkfire', 'Luvitus', 'Madisonwilleatu', 'mangadj', 'marett', 'Mayahem', 'Meman5000', 'MinecraftMasterz', 'MineMan_620', 'MisterModerator', 'Muro45', 'Napalm_Bomb', 'NoahWeasley', 'Nomnomguy', 'OmnipotentBeing_', 'OneHappyIgloo', 'OpelSpeedster', 'pokemon_pie', '', 'RandomIdoit', 'Redwild10', 'Rochambo', 'roebuck', 'RtYyU36', 'runnerman1', 'SavageClown', 'Schmoople', 'ScrooLewse', 'scyp10', 'Serus Haralain', 'Sgt. Traveler', 'silverboyp', 'Snerus', 'Space_Walker', 'SpeedyAstro', 'Syfaro', 'Synchrophi', 'Techdolpihn', 'TehEPICD00D', 'TEH_CATFACE', 'TheAverageForumUser', 'TheChillPixel', 'TheEpicBlock', 'TheKingOPower', 'The_Other_Retard', 'Travuersa', 'UnmaskedDavid', 'Usernamenotfound', 'Usoka', 'ValkonX11', 'Warior135', 'WarSyndrome', 'Wazdorf', 'woowoo678', 'Xboy001', 'X_Paragon_X', 'Yad', 'Zakhep', 'Zoropie5', 'Zkyo'];
//        return $usernames[mt_rand(0, count($usernames))];
//    }

    /*
     *
     * public function flip(Request $request) {
        $items = collect([]);
        foreach($request->itemIds as $itemId) {
            //fix this to make 1 query to DB instead of 10 in this case
            $item = Item::with('baseItem.icase')->find($itemId);
//            $flights = App\Flight::find([1, 2, 3]);
            $items->push($item);
        }
        $totalMoney = $items->sum('price');
        $lowestMoney = $totalMoney - $totalMoney * 0.1;
        $highestMoney = $totalMoney + $totalMoney * 0.1;
        $randWeapon = Item::whereBetween('price', [$lowestMoney, $highestMoney])->get()->random();
        $totalEnemyMoney = $randWeapon->price;
        $victory = (int)(mt_rand(0, ($totalMoney * 100) + ($totalEnemyMoney * 100)) < ($totalMoney * 100));
        $tSide = ($request->tSide === 'true') ? 1 : 0;
        $dopAngle = ($victory == $tSide) ? 1 : 0;
        $angle = 180 * (10 + $dopAngle);
        if($victory) {
            auth()->user()->items()->attach($randWeapon->id);
        }
        else {
            foreach($items as $item) {
                ItemUser::where(['user_id' => auth()->user()->id, 'item_id' => $item->id])->first()->delete();
            }
        }
        $wonItems = [];
        $wonItem['image'] = $randWeapon->image;
        $wonItem['rarity'] = strtolower($randWeapon->baseItem->rarity->title);
        $wonItem['title'] = $randWeapon->middle_title;
        $wonItem['price'] = $randWeapon->price;
        $wonItem['condition'] = $randWeapon->condition->title;
        $wonItem['stattrak'] = $randWeapon->stattrak;
        $wonItems[] = $wonItem;
        $enemyRand = Bot::all()->random();
        $enemy = ['username' => $enemyRand->username, 'avatar' => $enemyRand->avatar];
        $data = [$wonItems, $victory, $angle, $enemy, $tSide];
        return response()->json($data);
    }
     */
}