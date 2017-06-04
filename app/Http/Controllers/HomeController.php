<?php

namespace App\Http\Controllers;

use App\CoinflipLog;
use App\ContractLog;
use App\Item;
use App\ItemAbstract;
use App\ItemCondition;
use App\ItemCase;
use App\ItemName;
use App\ItemPattern;
use App\RareAbstract;
use App\Weapon;
use App\WeaponAbstract;
use App\Rare;
use App\ItemUser;
use App\ItemRarity;
use Illuminate\Http\Request;
use Symfony\Component\DomCrawler\Crawler;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $items = auth()->user()->items;
        return view('home', compact('items'));
    }

    public function testCase(ItemCase $case) {
        dd($case);
    }

    public function test() {
        $avg = 4.2;
        $minCondition = 1;
        $maxCondition = 3;
        $bottom = (int)ceil($avg) > $maxCondition ? $maxCondition : (int)ceil($avg);
        $top = $minCondition;
        if($bottom < $minCondition) {
            $top = $bottom = $minCondition;
        }
        if($bottom > $minCondition) {
            $top = $bottom = $maxCondition;
        }
        echo '<br> [' . $minCondition . '; ' . $maxCondition . ']<br>';
        echo 'avg: ' . $avg . ' .<br> [' . $top . '; ' . $bottom . ']<br>';
//        $ceilAvg = (int)ceil($avg);
//        if($ceilAvg < $maxCondition)

        dd((int)ceil($avg));
    }

    public function test230() {
            $items = ItemCase::where('id', 40)->first()->items;
            $rarityChances5 = [79.539, 16.424, 3.257, 0.553, 0.227];
//        $rarityChances6 = [79.539, 10.424, 6, 3.257, 0.553, 0.227];
            $rarityChances6 = [79.539, 89.963, 95.963, 99.220, 99.773, 100];

            if(ItemCase::find(40)->collection) {
                $maxRarity = $items->max('rarity_id');
                $minRarity = $items->min('rarity_id');
                $rarities = ItemRarity::where('id', '<', $maxRarity)->get();
                $rouletteItems = collect([]);
//                dd($minRarity);
            for($i = 0; $i < 35; $i++) {
                $baseItem = $items->where('rarity_id', mt_rand($minRarity, $maxRarity))->random();

//                $randRarity = rand(0, 10000) / 100;
//                if ($randRarity < $rarityChances6[0]) {
//                    $baseItem = $items->where('rarity_id', $rarities[0]->id)->random();
//                } elseif ($randRarity < $rarityChances6[1]) {
//                    $baseItem = $items->where('rarity_id', $rarities[1]->id)->random();
//                } elseif ($randRarity < $rarityChances6[2]) {
//                    $baseItem = $items->where('rarity_id', $rarities[2]->id)->random();
//                } elseif ($randRarity < $rarityChances6[3]) {
//                    $baseItem = $items->where('rarity_id', $rarities[3]->id)->random();
//                } else {
//                    $baseItem = $items->where('rarity_id', $rarities[4]->id)->random();
//                }
                $randStattrak = (int)(mt_rand(1, 10) == 10);
                if(! ItemCase::find(40)->souvenir) {
                    $randStattrak = 0;
                }
                $randCondition = $baseItem->conditions[mt_rand(0, count($baseItem->conditions) - 1)];
                $item = $baseItem->childItems->where('souvenir', $randStattrak)->where('condition_id', $randCondition)->first();

                $rouletteItems->prepend($item);
            }
            }
            dd($rouletteItems);
    }

    public function test228() {
        $items = ItemCase::where('id', 23)->first()->items;
        $rarityChances5 = [79.539, 16.424, 3.257, 0.553, 0.227];
        $rarityChances6 = [79.539, 89.963, 95.963, 99.220, 99.773, 100];
        $maxRarity = $items->last()->id;
        $rarities = ItemRarity::where('id', '<', $maxRarity)->get();
        $rouletteItems = collect([]);
        for($i = 0; $i < 35; $i++) {
            $randRarity = rand(0, 10000) / 100;
            if ($randRarity < $rarityChances6[0]) {
                $baseItem = $items->where('rarity_id', $rarities[0]->id)->random();
            } elseif ($randRarity < $rarityChances6[1]) {
                $baseItem = $items->where('rarity_id', $rarities[1]->id)->random();
            } elseif ($randRarity < $rarityChances6[2]) {
                $baseItem = $items->where('rarity_id', $rarities[2]->id)->random();
            } elseif ($randRarity < $rarityChances6[3]) {
                $baseItem = $items->where('rarity_id', $rarities[3]->id)->random();
            } else {
                $baseItem = $items->where('rarity_id', $rarities[4]->id)->random();
            }
            $randStattrak = (int)(mt_rand(1, 10) == 10);
            if(! ItemCase::find(23)) {
                $randStattrak = 0;
            }
            $randCondition = $baseItem->conditions[mt_rand(0, count($baseItem->conditions) - 1)];
            $item = $baseItem->childItems->where('souvenir', $randStattrak)->where('condition_id', $randCondition)->first();

            $rouletteItems->prepend($item);
        }
        dd($rouletteItems);

    }

    public function test___() {
        $str = '';
        for ($i = 465; $i < 501; $i++) {
            $str .= "['case_id' => '19', 'item_abstract_id' => '" . $i . "', 'created_at' => \Carbon\Carbon::createFromDate(2013, 8, 14), 'updated_at' => \Carbon\Carbon::createFromDate(2013, 8, 14)],<br>";
        }
        echo $str;
    }

    public function test_knife_exact() {
        ini_set('max_execution_time', 999999);
        $BufCrawler = new Crawler(file_get_contents('https://csgostash.com/gloves'));
        $itemsLinks = $BufCrawler->filter('.details-link p a')->each(function (Crawler $node, $i) {
            return $node->attr('href');
        });
//        $itemsLinks = array_slice($itemsLinks, 1, 6);
        $itemsLinks = array_reverse($itemsLinks);
        foreach($itemsLinks as $url) {

            $crawler = new Crawler(file_get_contents($url));

            $itemWeaponName = $crawler->filter('h2 a')->first()->text();
            $itemPatternName = $crawler->filter('h2 a')->last()->text();
            if($itemPatternName == '★ (Vanilla)') $itemPatternName = 'Vanilla';
            $itemsST = $crawler->filter('.price-details-table tbody tr')->each(function (Crawler $node, $i) {
                return $node->filter('td')->eq(0)->filter('.price-details-st-nomargin')->count() ? 1 : 0;
            });

            $itemsConditions = $crawler->filter('.price-details-table tbody tr')->each(function (Crawler $node, $i) {
                return str_ireplace('StatTrak ', '', trim(preg_replace('/\t+/', '', $node->filter('td')->eq(0)->text())));
            });

            $itemsPrices = $crawler->filter('.price-details-table tbody tr')->each(function (Crawler $node, $i) {
                return strpos(trim(str_ireplace(['\t', '\n', '"'], '', $node->filter('td')->eq(1)->text())), ' ') ? trim(str_ireplace(['\t', '\n', '"', '₴', ' '], '', $node->filter('td')->eq(1)->text())) : trim(str_ireplace(['\t', '\n', '"'], '', $node->filter('td')->eq(1)->text()));
            });

//            $itemsLinkz = $crawler->filter('.price-details-table tbody tr')->each(function (Crawler $node, $i) {
//                return $node->filter('td .market-button-skin')->count() ? $node->filter('td .market-button-skin')->attr('href') : false;
//            });

//            $imagesLinks = [];
//            foreach($itemsLinkz as $itemLink) {
//                sleep(30);
//
//                if($itemLink) {
//                    $steamCrawler = new Crawler(file_get_contents($itemLink));
//                    if($steamCrawler->filter('.market_listing_largeimage img')->count()) {
//                        $imagesLinks[] = str_ireplace('/360fx360f', '', $steamCrawler->filter('.market_listing_largeimage img')->first()->attr('src'));
//                    } else $imagesLinks[] = false;
//                } else $imagesLinks[] = false;
//            }
            $itemId = ItemAbstract::where('image', str_ireplace(' ', '_', strtolower($itemWeaponName)) . '_' . str_ireplace(' ', '_', strtolower($itemPatternName)) . '.png')->first()->id;

            $items = [];
            for ($i = 0; $i < count($itemsST); $i++) {
                $item = [];
                $item['weaponName'] = $itemWeaponName;
                $item['patternName'] = $itemPatternName;
                $item['stattrak'] = $itemsST[$i];
                $item['condition'] = ($itemsConditions[$i] == 'Vanilla') ? 'Factory New' : $itemsConditions[$i];
//                $item['condition'] = $itemsConditions[$i];
                $item['price'] = number_format($itemsPrices[$i] / 27.22, 2);
//                $item['price'] = $itemsPrices[$i];
//                $item['imageLink'] = $imagesLinks[$i];
                $item['image'] = str_ireplace(' ', '_', strtolower($itemWeaponName)) . '_' . str_ireplace(' ', '_', strtolower($itemPatternName)) . '_' . strtolower($this->getAbbrCondition($itemsConditions[$i])) . '.png';
                $items[] = $item;
                $strBuf = "['item_abstract_id' => '" . $itemId . "', 'condition_id' => '" . $this->getIntCondition($item['condition']) . "', 'stattrak' => " . $item['stattrak'] . ", 'souvenir' => false, 'price' => '" . $item['price'] . "', 'image' => '" . $item['image'] . "', 'created_at' => \Carbon\Carbon::createFromDate(2013, 8, 14), 'updated_at' => \Carbon\Carbon::createFromDate(2013, 8, 14)], \n";
                file_put_contents('storage/images/itemz/buf.txt', $strBuf, FILE_APPEND);
                file_put_contents('buf.txt', $strBuf, FILE_APPEND);

            }
//            sleep(2);

//            for($i = count($items) - 1; $i <= count($items) / 2; $i++) {
//                sleep(30);
//
//                if($items[$i]['imageLink']) {
//                    copy($items[$i]['imageLink'], 'storage/images/itemz/' . $items[$i]['image']);
//                }
//            }
        }
    }

    public function test_knife() {
        ini_set('max_execution_time', 999999);
        $url = 'https://csgostash.com/gloves';
        $crawler = new Crawler(file_get_contents($url));
        $knifeName = str_ireplace(' Skins', '', $crawler->filter('h1')->text());

//        $itemsWeaponNames = $crawler->filter('.result-box h3')->each(function (Crawler $node, $i) {
//            return $node->filter('a')->eq(0)->text();
//        });
        $itemsPatternNames = $crawler->filter('.result-box h3')->each(function (Crawler $node, $i) {
            return $node->filter('a')->text();
        });

//        $itemsPatternNames[0] = 'Vanilla';


//        $itemsRarities = $crawler->filter('.quality .nomargin')->each(function (Crawler $node, $i) {
//            return explode(' ', $node->text())[0];
//        });


        $itemsImagesLinks = $crawler->filter('.result-box .img-responsive')->each(function (Crawler $node, $i) {
            return $node->attr('src');
        });

//        dd($itemsImagesLinks);

        $str = '';
        for($i = count($itemsPatternNames) - 1; $i >= 0; $i--) {
//            if(in_array($itemsPatternNames[$i], ['Lore', 'Black Laminate', 'Gamma Doppler', 'Autotronic', 'Bright Water', 'Freehand'])) {
                $itemWeaponNameId = ItemName::where('title', $knifeName)->first()->id;
                $itemPatternNameId = ItemPattern::where('title', $itemsPatternNames[$i])->first()->id;
                $itemRarityId = 7;
                $str .= "['item_name_id' => '" . $itemWeaponNameId . "', 'item_pattern_id' => '" . $itemPatternNameId . "', 'rarity_id' => '" . $itemRarityId . "', 'image' => '" . str_ireplace(' ', '_', strtolower($knifeName)) . "_" . str_ireplace(' ', '_', strtolower($itemsPatternNames[$i])) . ".png', 'created_at' => \Carbon\Carbon::createFromDate(2013, 8, 14), 'updated_at' => \Carbon\Carbon::createFromDate(2013, 8, 14)],<br>";
                copy($itemsImagesLinks[$i], 'storage/images/itemz/' . str_ireplace(' ', '_', strtolower($knifeName)) . "_" . str_ireplace(' ', '_', strtolower($itemsPatternNames[$i])) . ".png");
//            sleep(5);
//            }
        }

        echo $str;
    }

    public function test_() {
        ini_set('max_execution_time', 999999);
        $url = 'https://csgostash.com/collection/The+Vertigo+Collection';
        $crawler = new Crawler(file_get_contents($url));

        $itemsWeaponNames = $crawler->filter('.result-box h3')->each(function (Crawler $node, $i) {
            return $node->filter('a')->eq(0)->text();
        });
        $itemsPatternNames = $crawler->filter('.result-box h3')->each(function (Crawler $node, $i) {
            return $node->filter('a')->eq(1)->text();
        });


        $itemsRarities = $crawler->filter('.quality .nomargin')->each(function (Crawler $node, $i) {
            return explode(' ', $node->text())[0];
        });


        $itemsImagesLinks = $crawler->filter('.result-box .img-responsive.margin-top-sm')->each(function (Crawler $node, $i) {
            return $node->attr('src');
        });

        $str = '';
        for($i = count($itemsWeaponNames) - 1; $i >= 0; $i--) {
            $itemWeaponNameId = ItemName::where('title', $itemsWeaponNames[$i])->first()->id;
            $itemPatternNameId = ItemPattern::firstOrCreate(['title' => $itemsPatternNames[$i]])->id;
            $itemRarityId = ItemRarity::where('title', $itemsRarities[$i])->first()->id;
            $str .= "['item_name_id' => '" . $itemWeaponNameId . "', 'item_pattern_id' => '" . $itemPatternNameId . "', 'rarity_id' => '" . $itemRarityId . "', 'image' => '" . str_ireplace(' ', '_', strtolower($itemsWeaponNames[$i])) . "_" . str_ireplace(' ', '_', strtolower($itemsPatternNames[$i])) . ".png', 'created_at' => \Carbon\Carbon::createFromDate(2013, 8, 14), 'updated_at' => \Carbon\Carbon::createFromDate(2013, 8, 14)],<br>";
            copy($itemsImagesLinks[$i], 'storage/images/itemz/' . str_ireplace(' ', '_', strtolower($itemsWeaponNames[$i])) . "_" . str_ireplace(' ', '_', strtolower($itemsPatternNames[$i])) . ".png");
//            sleep(1);
        }

        echo $str;
    }

    public function test__() {
        ini_set('max_execution_time', 999999);
        $souvenir = false;

        $BufCrawler = new Crawler(file_get_contents('https://csgostash.com/collection/The+Vertigo+Collection'));
        if($BufCrawler->filter('.souvenir')->count())
            $souvenir = true;
        $itemsLinks = $BufCrawler->filter('.details-link p a')->each(function (Crawler $node, $i) {
            return $node->attr('href');
        });
        //  $itemsLinks = array_slice($itemsLinks, 0, 1);

        $itemsLinks = array_reverse($itemsLinks);
//        dd($itemsLinks);
        foreach($itemsLinks as $url) {

            $crawler = new Crawler(file_get_contents($url));

            $itemWeaponName = $crawler->filter('h2 a')->first()->text();
//            $itemPatternName = $crawler->filter('h2 a')->last()->text() == '龍王 (Dragon King)' ? '(Dragon King)' : $crawler->filter('h2 a')->last()->text();
            $itemPatternName = $crawler->filter('h2 a')->last()->text();

            $itemsST = $crawler->filter('.price-details-table tbody tr')->each(function (Crawler $node, $i) {
                return $node->filter('td')->eq(0)->filter('.price-details-souv-nomargin')->count() ? 1 : 0;
            });

            $itemsConditions = $crawler->filter('.price-details-table tbody tr')->each(function (Crawler $node, $i) {
                return str_ireplace('Souvenir ', '', trim(preg_replace('/\t+/', '', $node->filter('td')->eq(0)->text())));
            });

            $itemsPrices = $crawler->filter('.price-details-table tbody tr')->each(function (Crawler $node, $i) {
                return (int)trim(preg_replace('/\t+/', '', $node->filter('td')->eq(1)->text()));
            });

            $itemsLinkz = $crawler->filter('.price-details-table tbody tr')->each(function (Crawler $node, $i) {
                return $node->filter('td .market-button-skin')->attr('href');
            });

            $imagesLinks = [];
            foreach($itemsLinkz as $itemLink) {
                $steamCrawler = new Crawler(file_get_contents($itemLink));
                sleep(10);
                if($steamCrawler->filter('.market_listing_largeimage img')->count())
                    $imagesLinks[] = str_ireplace('/360fx360f', '', $steamCrawler->filter('.market_listing_largeimage img')->first()->attr('src'));
                else $imagesLinks[] = 'http://orig14.deviantart.net/1c73/f/2015/035/5/c/the_forest_png_icon_by_vezty-d88qazl.png';
            }
            $itemId = ItemAbstract::where('image', str_ireplace(' ', '_', strtolower($itemWeaponName)) . '_' . str_ireplace(' ', '_', strtolower($itemPatternName)) . '.png')->first()->id;

            $items = [];
            for($i = 0; $i < count($itemsST); $i++) {
                $item = [];
                $item['weaponName'] = $itemWeaponName;
                $item['patternName'] = $itemPatternName;
                $item['stattrak'] = $itemsST[$i];
                $item['condition'] = $itemsConditions[$i];
                $item['price'] = $itemsPrices[$i];
                $item['imageLink'] = $imagesLinks[$i];
                $item['image'] = str_ireplace(' ', '_', strtolower($itemWeaponName)) . '_' . str_ireplace(' ', '_', strtolower($itemPatternName)) . '_' . strtolower($this->getAbbrCondition($itemsConditions[$i])) . '.png';
                $items[] = $item;

                $strBuf = "['item_abstract_id' => '" . $itemId . "', 'condition_id' => '" . $this->getIntCondition($item['condition']) . "', 'stattrak' => false, 'souvenir' => " . $item['stattrak'] . ", 'price' => '" . number_format($item['price'] / 27.22, 2) . "', 'image' => '" . $item['image'] . "', 'created_at' => \Carbon\Carbon::createFromDate(2013, 8, 14), 'updated_at' => \Carbon\Carbon::createFromDate(2013, 8, 14)], \n";
                file_put_contents('storage/images/itemz/buf.txt', $strBuf, FILE_APPEND);
                file_put_contents('buf.txt', $strBuf, FILE_APPEND);

            }

            if($souvenir)
                $countItems = count($items) / 2;
            else
                $countItems = count($items);
            for($i = 0; $i < $countItems; $i++) {
                copy($items[$i]['imageLink'], 'storage/images/itemz/' . $items[$i]['image']);

                sleep(10);
            }
        }
    }

    public function itemsToString($items) {
        $str = '// ' . $items[0]['weaponName'] . ' ' . $items[0]['patternName'] . '<br>';
        $itemId = ItemAbstract::where('image', str_ireplace(' ', '_', strtolower($items[0]['weaponName'])) . '_' . str_ireplace(' ', '_', strtolower($items[0]['patternName'])) . '.png')->first()->id;
        foreach($items as $item) {
            $strBuf = "['item_abstract_id' => '" . $itemId . "', 'condition_id' => '" . $this->getIntCondition($item['condition']) . "', 'stattrak' => " . $item['stattrak'] . ", 'souvenir' => false, 'price' => '" . number_format($item['price'] / 27.22, 2) . "', 'image' => '" . $item['image'] . "', 'created_at' => \Carbon\Carbon::createFromDate(2013, 8, 14), 'updated_at' => \Carbon\Carbon::createFromDate(2013, 8, 14)], \n";
            $str .= $strBuf;
            file_put_contents('storage/images/itemz/buf.txt', $strBuf, FILE_APPEND);
        }
        return $str;
    }
/*
 * 1. Get Steam Market link
 * 2. Loop for each item in collection of conditions
 * 3. Get block for an item
 * 4. Get img src link
 * 5. Remove last symbols (which tells it's size)
 * 6. Give that link to download function (like copy, mentioned on SO)
 * 7. Name it as weaponname_patternname_condition_abbr0
 */
    public function test39() {
        $url = 'https://csgostash.com/skin/135/SCAR-20-Crimson-Web';
//        $url = 'https://csgostash.com/skin/126/M4A1-S-Blood-Tiger';
        $crawler = new Crawler(file_get_contents($url));

        $itemWeaponName = $crawler->filter('h2 a')->first()->text();
        $itemPatternName = $crawler->filter('h2 a')->last()->text();

        $itemsCount = $crawler->filter('.active .market-button-skin .pull-right')->each(function (Crawler $node, $i) {
            return ($node->text() == 'Not Possible') ? 1 : 0;
        });
        $itemsAmount = array_count_values($itemsCount)[0];

        $itemsST = $crawler->filter('.active .market-button-skin')->each(function (Crawler $node, $i) {
            return $node->filter('.price-details-st')->count() ? 0 : 1;
        });

        $itemsPrices = $crawler->filter('.active .market-button-skin .pull-right')->each(function (Crawler $node, $i) {
            return $node->text();
        });

        $itemsConditions = $crawler->filter('.active .market-button-skin .pull-left')->each(function (Crawler $node, $i) {
            return ($node->text())  ;
        });
        $itemsConditions = array_values(array_diff($itemsConditions, ['StatTrak']));

        $items = [];
        for($i = 0; $i < 10; $i++) {
            if(! $itemsCount[$i]) {
                $item = [];
                $item['weaponName'] = $itemWeaponName;
                $item['patternName'] = $itemPatternName;
                $item['stattrak'] = $itemsST[$i];
                $item['condition'] = $itemsConditions[$i];
                $item['image'] = str_ireplace(' ', '_', strtolower($itemWeaponName)) . '_' . str_ireplace(' ', '_', strtolower($itemPatternName)) . '_' . strtolower($this->getAbbrCondition($itemsConditions[$i])) . '.png';
                $item['price'] = $itemsPrices[$i];
                $items[] = $item;
            }
        }

        $steamListingsURL = $crawler->filter('.price-bottom-space .market-button-skin')->first()->attr('href');
        $urls = [];
        $urls[] = 'https://steamcommunity.com/market/listings/730/SCAR-20%20%7C%20Crimson%20Web%20%28Field-Tested%29';
//        $urls[] = 'https://steamcommunity.com/market/listings/730/' . ;
//        dd($steamListingsURL);
        $steamCrawler = new Crawler(file_get_contents($steamListingsURL));
        foreach($items as $item) {
//            $steamCrawler->filter($item['weaponName'] . ' | ' . $item['patternName'] . '(' . $item['condition'] . ')')->parents()->filter('.market_listing_item_img')->first()->attr('src');
            $steamCrawler->filter('.market_listing_item_name');
            print_r();
        }
//        dd($items);
    }

    private function getAbbrCondition($longCondition) {
        switch($longCondition) {
            case 'Factory New': return 'FN'; break;
            case "Minimal Wear": return 'MW'; break;
            case 'Field-Tested': return 'FT'; break;
            case 'Well-Worn': return 'WW'; break;
            case 'Battle-Scarred': return 'BS'; break;
        }
    }

    private function getIntCondition($longCondition) {
        switch($longCondition) {
            case 'Factory New': return '1'; break;
            case "Minimal Wear": return '2'; break;
            case 'Field-Tested': return '3'; break;
            case 'Well-Worn': return '4'; break;
            case 'Battle-Scarred': return '5'; break;
        }
    }

    public function test38() {
        $exItem = collect([]);
        $crawler = new Crawler(file_get_contents('https://csgostash.com/case/4/CS:GO-Weapon-Case-2'));
        $itemsLinks = $crawler->filter('.market-button-skin')->each(function (Crawler $node, $i) {
            return $node->attr('href');
        });
        /*
         $itemsNames = $crawler->filter('.details-link p a')->each(function (Crawler $node, $i) {
            return str_ireplace(' Skin & Price Details', '', $node->text());
        });
        */

        $items = collect([]);
        foreach($itemsLinks as $link) {
            $item = collect(['link' => $link]);
            $items->push($item);
        }
        dd($items);
    }

    public function test37() {
        $items = Item::join('coinflip_item', 'coinflip_item.item_id', '=', 'items.id')
            ->join('coinflip_logs', 'coinflip_logs.id', '=', 'coinflip_item.coinflip_id')
            ->get();
        $items = $items->groupBy('coinflip_id');
        $coinflips= CoinflipLog::orderBy('id', 'desc')->get();
        dd($coinflips);
    }

    public function test36() {
        $usernames = ['Sgt. Traveler', 'The_Other_Retard', 'CandyRaid', '0Zero0', '15avigil', '447u', '~sound~wave~', 'AirFusion', 'AmazingHuh', 'aranamor', 'atomic7732', 'Awesomus Maximus', 'Billybobjoepants', 'BioShock_Rules', 'Blanzer', 'Bob-Omb', 'bosky2102', 'Brendan170', 'broswen', 'Btrpo', 'bubbyboytoo', 'CandyRaid', 'Carmelpoptart', 'catlover2011', 'cejj99', 'Chan14551', 'ChowderBowl', 'Chzydeath', 'CoolBlueJ', 'cupcakes_rock', 'DaBomb', 'Darvince', 'DaSnipeKid', 'Dawnofdusk', 'De_n00bWOLF', 'DeepDarkSamurai', 'DefaultAsAwesome', 'diamondhand146', 'DirtDog', 'DivinityV2', 'DontStealMyBacon', 'Draconus', 'Dylanf3', 'Em0srawk', 'enderfemale', 'Entophobia', 'Erak606', 'F0R1', 'faloxx', 'fire3232', 'firehawk729', 'Firestix', 'Fixin', 'For_the_lolz', 'FoxHound42', 'Gail102', 'GamingChanging', 'geez', 'georgeyves', 'goodatthis123', 'grox19', 'haltyoudoglovers', 'Hideki Ryuga', 'iiTzWho', 'ii{i(DELTA)i}ii', 'IkubiAkius', 'ize]KuruKuruGuy', 'JaffaHunter', 'Jax4321', 'JesusoChristo', 'JRyno', 'KillerPlant', 'KyoDemer', 'Leostereo', 'LMMN', 'LokiDarkfire', 'Luvitus', 'Madisonwilleatu', 'mangadj', 'marett', 'Mayahem', 'Meman5000', 'MinecraftMasterz', 'MineMan_620', 'MisterModerator', 'Muro45', 'Napalm_Bomb', 'NoahWeasley', 'Nomnomguy', 'OmnipotentBeing_', 'OneHappyIgloo', 'OpelSpeedster', 'pokemon_pie', '', 'RandomIdoit', 'Redwild10', 'Rochambo', 'roebuck', 'RtYyU36', 'runnerman1', 'SavageClown', 'Schmoople', 'ScrooLewse', 'scyp10', 'Serus Haralain', 'Sgt. Traveler', 'silverboyp', 'Snerus', 'Space_Walker', 'SpeedyAstro', 'Syfaro', 'Synchrophi', 'Techdolpihn', 'TehEPICD00D', 'TEH_CATFACE', 'TheAverageForumUser', 'TheChillPixel', 'TheEpicBlock', 'TheKingOPower', 'The_Other_Retard', 'Travuersa', 'UnmaskedDavid', 'Usernamenotfound', 'Usoka', 'ValkonX11', 'Warior135', 'WarSyndrome', 'Wazdorf', 'woowoo678', 'Xboy001', 'X_Paragon_X', 'Yad', 'Zakhep', 'Zoropie5', 'Zkyo'];
        $loop = 0;
        if ($handle = opendir('../storage/app/public/images/avatars')) {
            while (false !== ($entry = readdir($handle))) {
                if ($entry != "." && $entry != "..") {
                    $str = "['username' => '" . $usernames[$loop] . "', 'avatar' => '" . $entry . "', 'created_at' => \Carbon\Carbon::now(), 'updated_at' => \Carbon\Carbon::now()];<br>";
                    echo $str;
                }
                $loop++;
            }
            closedir($handle);
        }
    }

    public function test35() {
//        $contracts = ContractLog::with('items')->get();
//        dd($contracts->first());
        $items = Item::join('contract_item', 'contract_item.user_item_id', '=', 'items.id')
            ->join('contracts_logs', 'contracts_logs.id', '=', 'contract_item.contract_id')
            ->get();
        $items = $items->groupBy('contract_id');
        $contracts = ContractLog::all();
        dd($contracts);
    }

    public function test34() {
        $rolls = auth()->user()->rolls;
        dd($rolls->first()->profit);
    }

    public function test33() {
        $cases = ItemCase::with('topCoverts')->get();
        dd($cases);
    }

    public function test32()
    {
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
        $knivesGroupped = $knivesGroupped->groupBy('item_name_id')->sort()->values();
        dd($knivesGroupped[1]);
    }

    public function test31() {
//        $karambits = ItemName::where('title', 'Karambit')->first()->items()->with('childItems')->get();
//        $karambitsName = ItemName::where('title', 'Karambit')->first();
//        $karambits = $karambitsName->itemz()->where('condition_id', 1)->orderBy('price', 'desc')->get();
//        $knivesNames = ItemName::where('item_type_id', 5)->with('itemz')->get();
//        $knives = $knivesNames->where('condition_id', 1)->groupBy('item_name_id');
        $knives = Item::join('items_abstract', 'items_abstract.id', '=', 'items.item_abstract_id')
            ->join('items_names', 'items_abstract.item_name_id', 'items_names.id')
            ->where('items_names.item_type_id', 5)
            ->where('items.condition_id', 1)
            ->orderBy('items.price', 'desc')
            ->get();
        $knives = $knives->groupBy('title');
        dd($knives);
    }

    public function test30() {
        $knives = ItemAbstract::where('rarity_id', 7)->get();
//        $knives = $knives->groupBy('id');
//        $knives = $knives->where('item_pattern_id', ItemPattern::where('title', 'Vanilla')->first()->id);
//        $knivez = collect([]);
//        foreach($knives as $knive) {
//            $knivez->push($knive);
//        }

        dd($knives);
    }

    public function test29() {
        $knives = Item::join('items_abstract', 'items_abstract.id', '=', 'items.item_abstract_id')
            ->where('rarity_id', 7)

            ->get();
        dd($knives);
    }

    public function test28() {
        $item = Item::find(671);
        dd($item->baseItem->best_condition);
    }

    public function test27() {
//        $cases = ItemCase::with('weaponz')->where('rarity_id', 5)->get();

//        $cases = ItemCase::with('coverts')->get();
//        $itemz = ItemCase::join('items_abstract', 'case_item.item_abstract_id', '=', 'case_item.case_id')
        /*
         * PRODUCT      ITEM.ABSTRACT
         * CATEGORY     ITEM.CASE
         * PROD_CAT     CASE_ITEM
         *
         * SELECT
                product.productID,
                category.categoryID,
                product.name,
                product.price,
                category.name
            FROM product
            JOIN product_cat ON product.productID = product_cat.productID
            JOIN category ON category.categoryID = product_cat.categoryID
         */
        $itemz = ItemAbstract::selectRaw('items_abstract.*')
            ->join('case_item', 'items_abstract.id', '=', 'case_item.item_abstract_id')
            ->join('items_cases', 'items_cases.id', '=', 'case_item.case_id')
            ->where('items_abstract.rarity_id', 6)
            ->groupBy('items_cases.id')
            ->get();
        $cases = ItemCase::with('coverts')->get();

        dd($cases);
    }

    public function test26() {
        $items = auth()->user()->items()
//            ->select('*')
//            ->select('*', 'count(*) as item_amount')
//            ->join('items_abstract', 'items_abstract.id', '=', 'items.item_abstract_id')
            ->selectRaw('items.*, count(*) as items_count')
            ->join('items_abstract', 'items_abstract.id', '=', 'items.item_abstract_id')
//            ->join('rares_abstract', 'rares_abstract.id', '=', 'rares.rares_abstract_id')
            ->orderBy('rarity_id', 'desc')
            ->orderBy('price', 'desc')
            ->groupBy('items.id')->get();
        dd($items);
    }

    public function test25() {
        $items = auth()->user()->weapons()
            ->selectRaw('items.*, count(*) as items_count')
            ->where('items_abstract.rarity_id', 4)
            ->where('items.stattrak', '0')
            ->groupBy('items.id')
            ->get();
        $casesItemsIds = [];
        foreach($items as $item)  {
            $casesItemsIds[] = $item->itemCase->first()->id;
        }
        $casesItemsIds = array_unique($casesItemsIds);
        $nextItemz = [];
        foreach($casesItemsIds as $caseItemId) {
            $items = ItemCase::find($caseItemId)->weapons()->where('rarity_id', 5)->get();
            $i = 0;
            foreach($items as $item) {
                $nextItemz[$caseItemId][$i]['image'] = $item->image;
                $nextItemz[$caseItemId][$i]['rarity'] = strtolower($item->rarity->title);
                $nextItemz[$caseItemId][$i]['weaponName'] = $item->WeaponName->title;
                $nextItemz[$caseItemId][$i]['weaponPattern'] = $item->WeaponPattern->title;
                $i++;
            }
        }
        dd($nextItemz);
    }

    public function test24() {
        $item = Item::find(579);
        $itemRarity = $item->baseItem->rarity->id + 1;
        $itemCollection = $item->itemCase->first()->id;
//        dd($itemCollection);
        $nextItems = ItemCase::find($itemCollection);
        dd($nextItems->weapons->where('rarity_id', $itemRarity));
    }

    public function test23() {
        $item = Item::find('579');
        dd($item->itemCase);
    }

    public function test22() {
        $rarity = ItemRarity::where('title', 'Classified')->first();
        dd($rarity->items()->where('stattrak', false)->get());
    }

    public function test21() {
        $dir = 'storage/images/avatars/';
        $images = scandir($dir);
        $i = rand(2, sizeof($images)-1);
        echo '<img src="' . asset('storage/images/avatars/' . $images[$i]) . '"></img>';
    }

    public function test20() {
        $items = collect([]);
//        foreach([521, 521, 521, 521, 521, 521, 521, 521, 521, 521] as $itemId) {
        foreach([409, 73, 73] as $itemId) {
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
//            if($totalMoney > $maxItemMoney) {
                $totalPrice = $totalMoney;
                for ($i = 0; $i < 10; $i++) {
                    if(($totalMoney > $maxItemMoney * 0.5) && ($i = 0)) {
//                    if(($i = 0) || ($maxItemMoney< $totalPrice * 10)) {
                        $bufItem = Item::orderBy('price', 'asc')->first();
                    } else {
                        for($j = 0.1; $j < 3.0; $j+=0.1) {
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
//                }
//            } else {
//                $enemyWeapons->push(Item::whereBetween('price', [$lowestMoney, $highestMoney])->get()->random());
            }
        } else {
            $enemyWeapons->push($itemz->random());
        }
//        if(! $randWeapon = Item::whereBetween('price', [$lowestMoney, $highestMoney])->get()->random() {
//        }

        dd($enemyWeapons->sum('price'));
    }

    public function test19() {
        $items = ItemCase::find(1)->weapons()->with('childItems')->get();
        $itemz = collect([]);
        foreach($items as $item) {
            foreach($item->childItems as $childItem) {
                $itemz->push($childItem);
            }
        }
//            $itemz->push($item->childItems);

//        $itemz = $items->childItems;
        $itemsAbstract = ItemCase::find(1)->weapons()->pluck('items_abstract.id')->toArray();
        $items = Item::whereIn('item_abstract_id', $itemsAbstract)
            ->select('items.*', 'items_abstract.rarity_id')
            ->join('items_abstract', 'items_abstract.id', '=', 'items.item_abstract_id')
//            ->join('items_rarities', 'items_rarities.id', '=', 'items_abstract.rarity_id')
            ->orderBy('items_abstract.rarity_id', 'desc')
            ->orderBy('items_abstract.item_name_id')
            ->orderBy('stattrak', 'desc')
            ->orderBy('condition_id', 'asc')
            ->get();
        $itemsGroups = $items->groupBy('rarity_id');
//        dd($items);
        dd($itemsGroups);
    }

    public function test18() {
//        $weapons = ItemCase::find(1)->weapons()->with('childItems')->get();
        $items = ItemCase::join('case_item', 'case_item.case_id', '=', 'items_cases.id')
            ->join('items', 'items.item_abstract_id', '=', 'case_item.item_abstract_id')
            ->join('items_abstract', 'items_abstract.id', '=', 'items.item_abstract_id')
            ->join('items_rarities', 'items_rarities.id', '=', 'items_abstract.rarity_id')
            ->where('items_abstract.rarity_id', '!=', '7')
            ->orderBy('items_abstract.rarity_id', 'desc')
            ->orderBy('items_abstract.item_name_id')
            ->orderBy('stattrak', 'desc')
            ->orderBy('condition_id', 'asc')
//            ->groupBy('items_abstract.rarity_id')
            ->get();
//        dd($items);
//        $itemz = Item::join('case_item', 'case_item.case_')
//        $itemz = Item::join('items_abstract', 'items_abstract.id', '=', 'items.item_abstract_id')
//            ->join('case_item', 'case_item.item_abstract_id', '=', 'items_abstract.id')
//            ->get();
//        dd($itemz);
        $itemsGroups = $items->groupBy('rarity_id');

        dd($itemsGroups->first());
    }

    public function test17() {
        $winnableItemAbstract = ItemCase::find(1)->weapons()->where('rarity_id', 5)->get()->random();
        dd($winnableItemAbstract);
    }

    public function test16() {
        $items = auth()->user()->weapons;
//        echo '<pre>';
        dd($items);
    }

    public function test15()
    {
        $items = ItemCase::where('id', 1)->first()->items;
        $rarities = ItemRarity::where('id', '>', '2')->get();
        for ($i = 0; $i < 1; $i++) {
            $randRarity = rand(0, 10000) / 100;
            if ($randRarity < $rarities[0]->drop_sum_rate) {
                $baseItem = $items->where('rarity_id', $rarities[0]->id)->random();
            } elseif ($randRarity < $rarities[1]->drop_sum_rate) {
                $baseItem = $items->where('rarity_id', $rarities[1]->id)->random();
            } elseif ($randRarity < $rarities[2]->drop_sum_rate) {
                $baseItem = $items->where('rarity_id', $rarities[2]->id)->random();
            } elseif ($randRarity < $rarities[3]->drop_sum_rate) {
                $baseItem = $items->where('rarity_id', $rarities[3]->id)->random();
            } else {
                $baseItem = $items->where('rarity_id', $rarities[4]->id)->random();
            }
            dd($baseItem);
            $randStattrak = (int)(mt_rand(1, 10) == 10);
            $randCondition = $baseItem->conditions[mt_rand(0, count($baseItem->conditions) - 1)];
            $item = $baseItem->childItems->where('stattrak', $randStattrak)->where('condition_id', $randCondition)->first();
            dd($item);
        }
    }

    public function test14() {
        $case = ItemCase::first();
        $items = $case->weapons;
        return $items;
//        dd($items);
    }

    public function test13() {
        // CT   LOST 180
//          0   0
        // CT   WIN 0
//          0   1
        // T    LOST 0
//          1   0
        // T    WIN 180
//          1   1
        dd(0 && 0);
    }

    public function test12() {
        dd(mt_rand(0, 1));
    }

    public function test11() {
        $items = collect([]);
        foreach([1, 2, 3, 4, 6] as $itemId) {
            //fix this to make 1 query to DB instead of 10 in this case
            $item = Weapon::with('baseItem.icase')->find($itemId);
            $items->push($item);
        }
        $totalMoney = $items->sum('price');
        $lowestMoney = $totalMoney - $totalMoney * 0.1;
        $highestMoney = $totalMoney + $totalMoney * 0.1;
        $randWeapon = Weapon::whereBetween('price', [$lowestMoney, $highestMoney])->get()->random();

        dd($randWeapon);

    }

    public function test10() {
        $money = 100;
        $rares = Rare::whereBetween('price', [90, 110])->get()->random();

//        dd($rares);
        dd($rares);
    }

    public function test9() {
        $baseItem = ItemCase::where('id', 1)->first()->rares->random();
//        $baseItem = RareAbstract::all()->random();
        echo '<pre>';
        print_r($baseItem->conditions);

        print_r($baseItem);
//        dd($baseItem->conditions);
    }

    public function test8() {
        $rouletteItem = ItemCase::where('id', 1)->first()->rares->random();
        $rouletteItemsJS = [];
            $rouletteItemsJS[1]['image'] = $rouletteItem->image;
            $rouletteItemsJS[1]['rarity'] = strtolower($rouletteItem->baseItem->rarity->title);
            $rouletteItemsJS[1]['weaponName'] = $rouletteItem->baseItem->WeaponName->title;
            $rouletteItemsJS[1]['weaponPattern'] = $rouletteItem->baseItem->WeaponPattern->title;
            $rouletteItemsJS[1]['price'] = $rouletteItem->price;
            $rouletteItemsJS[1]['condition'] = $rouletteItem->condition->title;
            $rouletteItemsJS[1]['stattrak'] = $rouletteItem->stattrak;
        dd($rouletteItemsJS);
    }

    public function test7() {
        $item = Item::first();
        $baseItem = $item->baseItem;
        dd(min($baseItem->conditions));
        dd(Condition::min('id'));
    }

    public function test6() {
        $rarity = Rarity::where('title', 'Classified')->first();
//        $items = $rarity->items;
        $items = auth()->user()->items()
            ->join('items_abstract', 'items_abstract.id', '=', 'items.item_abstract_id')
            ->where('items_abstract.rarity_id', $rarity->id)
            ->get();
//        dd($items);
        return view('home', compact('items'));
//        dd($rarity->items);
    }

    public function test5() {
        $itemsIds = [7, 7, 1, 1, 16, 7, 16, 7, 16, 1];
        $caseIds = [];
        foreach($itemsIds as $itemId) {
            //fix this to make 1 query to DB instead of 10 in this case
            $item = Item::with('baseItem.icase')->find($itemId);
            $caseIds[] = $item->baseItem->icase->id;
//            $rarityIds[] = Item::find($itemId)
//                ->join('items_abstract', 'items_abstract.id', '=', 'items.item_abstract_id')
//                ->select('items_abstract.case_id')
//                ->get();
        }
//        dd(Item::find(1)
//            ->join('items_abstract', 'items_abstract.id', '=', 'items.item_abstract_id')
//            ->select('items_abstract.case_id')
//            ->get());
//        $caseIds = [1, 2, 3, 4, 5, 5, 5, 5, 6];
        $winnableCaseId = $caseIds[mt_rand(0, count($caseIds) - 1)];
        $winnableRarityId = (Item::with('baseItem.rarity')->find($itemsIds[0])->baseItem->rarity->id) + 1;
        $winnableStattrak = Item::find($itemsIds[0])->stattrak ? '1' : '0';
//        dd($winnableRarityId);
        $winnableItemAbstract = ItemAbstract::where('case_id', $winnableCaseId)->where('rarity_id', $winnableRarityId)->get()->random();
        $randCondition = $winnableItemAbstract->conditions[mt_rand(0, count($winnableItemAbstract->conditions) - 1)];
        $winnableItem = Item::where('item_abstract_id', $winnableItemAbstract->id)
            ->where('condition_id', $randCondition)
            ->where('stattrak', $winnableStattrak)
            ->first();
        //remove from users inventory 10 sent items
        //add to users inventory 1 new item
        $wonItem = [];
        $wonItem['image'] = $winnableItem->image;
        $wonItem['rarity'] = strtolower($winnableItem->baseItem->rarity->title);
        $wonItem['weaponName'] = $winnableItem->baseItem->WeaponName->title;
        $wonItem['weaponPattern'] = $winnableItem->baseItem->WeaponPattern->title;
        $wonItem['price'] = $winnableItem->price;
        $wonItem['condition'] = $winnableItem->condition->title;
        $wonItem['stattrak'] = $winnableItem->stattrak;
//        dd($wonItem);
//        $items = Item::find($itemsIds);
//        $cases = Item::;
    }

    public function test4() {
        $numbers = [1, 14, 2, 13, 3, 12, 4, 0, 11, 5, 10, 6, 9, 7, 8,
            1, 14, 2, 13, 3, 12, 4, 0, 11, 5, 10, 6, 9, 7, 8,
            1, 14, 2, 13, 3, 12, 4, 0, 11, 5, 10, 6, 9, 7, 8,
            1, 14, 2, 13, 3, 12, 4, 0, 11, 5, 10, 6, 9, 7, 8,
            1, 14, 2, 13, 3, 12, 4, 0, 11, 5, 10, 6, 9, 7, 8,
            1, 14, 2, 13, 3, 12, 4, 0, 11, 5, 10, 6, 9, 7, 8,
        ];

        $randNumber = mt_rand(count($numbers) - 15, count($numbers) - 2);
        echo 'rand number: ' . $numbers[$randNumber] . ' (' . $randNumber . ')<br>';
    }

    public function test3() {
        $item = Item::find(15);
        $user = auth()->user();
        $firstLink = ItemUser::where('item_id', 15)->get();
//        $firstLink = ItemUser::where(['user_id' => $user->id, 'item_id' => $item->id])->get();
        dd($firstLink);
        $user->items()->newPivotStatementForId($item->id)->where('id', $firstLink->id)->delete();
//        Item::find($request->itemid)->user()->newPivotStatementForId(Auth::id())->where('item_id', $item->id)->delete();
//        $user->items()->detach($item->id);
    }

    public function test2() {
        $rarities = Rarity::where('id', '>', '2')->get();
//        dd($rarities[0]->drop_rate + $rarities[1]->drop_rate + $rarities[2]->drop_rate + $rarities[3]->drop_rate);
//        dd($rarities->first()->test);

//        $case = Icase::first();
//        $item = $case->items->where('id', '7')->first();
//        dd($item->childItems->where('stattrak', '0'));
//        $items = $case->iitems->first();
//        $items->baseItem;
//        dd($items->baseItem);

        $items = Icase::where('id', 1)->first()->items;
        $rarities = Rarity::where('id', '>', '2')->get();
        $rouletteItems = collect([]);
        for($i = 0; $i < 30; $i++) {

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
            }
            $randStattrak = (int)(rand(1, 10) == 10);
            $randCondition = $baseItem->conditions[mt_rand(0, count($baseItem->conditions) - 1)];

            $item = $baseItem->childItems->where('stattrak', $randStattrak)->where('condition_id', $randCondition)->first();
            $rouletteItems->prepend($item);
        }

        $rouletteItemsJS = []; $count = 0;
        foreach($rouletteItems as $rouletteItem) {
            $rouletteItemsJS[$count]['image'] = $rouletteItem->image;
            $rouletteItemsJS[$count]['rarity'] = strtolower($rouletteItem->baseItem->rarity->title);
            $rouletteItemsJS[$count]['weaponName'] = $rouletteItem->baseItem->WeaponName->title;
            $rouletteItemsJS[$count]['weaponPattern'] = $rouletteItem->baseItem->WeaponPattern->title;
            $rouletteItemsJS[$count]['price'] = $rouletteItem->price;
            $rouletteItemsJS[$count]['condition'] = $rouletteItem->condition->title;
            $rouletteItemsJS[$count]['stattrak'] = $rouletteItem->stattrak;
//            $rouletteItemsJS[$count]['weaponPattern'] = $rouletteItem->weaponPattern;
            $count++;
        }
    }
}
