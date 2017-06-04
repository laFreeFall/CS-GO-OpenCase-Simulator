<?php

namespace App\Http\Controllers;

use App\RouletteBet;
use App\RouletteColor;
use App\RouletteLog;
use Illuminate\Http\Request;

class RouletteController extends Controller
{
    public function index() {
        $numbers = collect([
            ['value' => 1, 'color' => 'red'],
            ['value' => 14, 'color' => 'black'],
            ['value' => 2, 'color' => 'red'],
            ['value' => 13, 'color' => 'black'],
            ['value' => 3, 'color' => 'red'],
            ['value' => 12, 'color' => 'black'],
            ['value' => 4, 'color' => 'red'],
            ['value' => 0, 'color' => 'green'],
            ['value' => 11, 'color' => 'black'],
            ['value' => 5, 'color' => 'red'],
            ['value' => 10, 'color' => 'black'],
            ['value' => 6, 'color' => 'red'],
            ['value' => 9, 'color' => 'black'],
            ['value' => 7, 'color' => 'red'],
            ['value' => 8, 'color' => 'black'],
        ]);

        $lastRolls = RouletteLog::orderBy('id', 'desc')->take(10)->get()->reverse();

        return view('roulette.index', compact('numbers', 'lastRolls'));
    }

    public function getRouletteNumbers(Request $request) {
//        $allNumbers = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14];
//        $numbers = [1, 14, 2, 13, 3, 12, 4, 0, 11, 5, 10, 6, 9, 7, 8];
//        for ($i = 0; $i < 30; $i++) {
//            $randNumber = mt_rand(0, 14);
//            $numbers[] = $randNumber;
//        }

        $numbers = [1, 14, 2, 13, 3, 12, 4, 0, 11, 5, 10, 6, 9, 7, 8,
            1, 14, 2, 13, 3, 12, 4, 0, 11, 5, 10, 6, 9, 7, 8,
            1, 14, 2, 13, 3, 12, 4, 0, 11, 5, 10, 6, 9, 7, 8,
            1, 14, 2, 13, 3, 12, 4, 0, 11, 5, 10, 6, 9, 7, 8,
            1, 14, 2, 13, 3, 12, 4, 0, 11, 5, 10, 6, 9, 7, 8,
            1, 14, 2, 13, 3, 12, 4, 0, 11, 5, 10, 6, 9, 7, 8,
            1, 14, 2, 13, 3, 12, 4, 0, 11, 5, 10, 6, 9, 7, 8,
        ];

        $winnable['index'] = mt_rand(count($numbers) - 30, count($numbers) - 15);
        $winnable['number'] = $numbers[$winnable['index']];
        $winnable['color'] = $this->getWinnableColor($winnable['number']);

        // save roulette roll result to database
        $lastRoll = RouletteLog::create(['result' => $winnable['number']]);

        // save user's bet to database
        $colorsId = 1;
        foreach($request->colors as $color) {
            if($color > 0) {
                if($this->getWinnableNum($winnable['color']) == $colorsId)
                    $profit = $color * RouletteColor::find($colorsId)->multiplier;
                else
                    $profit = $color * -1;

//                $profit = ($this->getWinnableNum($winnable['color']) == $colorsId) ? $color * RouletteColor::find($colorsId)->multiplier : $color * -1;
                RouletteBet::create(['user_id' => auth()->user()->id, 'roll_id' => $lastRoll->id, 'roulette_color_id' => $colorsId, 'value' => $color, 'profit' => $profit]);
            }
            $colorsId++;
        }

        // react on user's bet
        $wonPoints['red'] = 0; $wonPoints['black'] = 0; $wonPoints['green'] = 0;
        // bet on RED
        if($request->colors['red']) {
            if($winnable['color'] == 'red') {
                $wonPoints['red'] = $request->colors['red'];
            } else {
                $wonPoints['red'] = $request->colors['red'] * (-1);
            }
        }
        // bet on BLACK
        if($request->colors['black']) {
            if($winnable['color'] == 'black') {
                $wonPoints['black']= $request->colors['black'];
            } else {
                $wonPoints['black'] = $request->colors['black'] * (-1);
            }
        }
        // bet on GREEN
        if($request->colors['green']) {
            if($winnable['color'] == 'green') {
                $wonPoints['green'] = $request->colors['green'] * 13;
            } else {
                $wonPoints['green'] = $request->colors['green'] * (-1);
            }
        }
        $wonPoints['total'] = $wonPoints['red'] + $wonPoints['black'] + $wonPoints['green'];
        $data = [$numbers, $winnable, $wonPoints];

        auth()->user()->points += $wonPoints['total'];
        auth()->user()->save();

        return response()->json($data);

    }

    private function getWinnableColor($winnableNumber) {
        if(($winnableNumber >= 1) && ($winnableNumber <= 7)) { // RED
            $winnableColor = 'red';
        }
        elseif(($winnableNumber >= 8) && ($winnableNumber <= 14)) { // BLACK
            $winnableColor = 'black';
        }
        else { // GREEN
            $winnableColor = 'green';
        }

        return $winnableColor;
    }

    private function getWinnableNum($winnableColor) {
        switch($winnableColor) {
            case 'red': return 1; break;
            case 'black': return 2; break;
            case 'green': return 3; break;
            default: return 0;
        }
    }

    public function logs() {
        $rolls = auth()->user()->rolls()->orderBy('created_at', 'desc')->paginate(10);
        $rollsAll = auth()->user()->rolls()->orderBy('created_at', 'desc')->get();
        return view('roulette.logs', compact('rolls', 'rollsAll'));
    }
}