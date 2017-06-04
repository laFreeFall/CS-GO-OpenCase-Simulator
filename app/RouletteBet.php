<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RouletteBet extends Model
{
    protected $table = 'roulette_bets';
    protected $fillable = ['roll_id', 'user_id', 'roulette_color_id', 'value', 'profit'];

    public function roll() {
        return $this->belongsTo('App\RouletteLog', 'roll_id');
    }

    public function user() {
        return $this->belongsTo('App\User');
    }

    public function bets() {
//        return $this->
    }

    public function color() {
        return $this->belongsTo('App\RouletteColor', 'roulette_color_id');
    }

    public function getBetColorAttribute() {
        return $this->color->title;
    }

}