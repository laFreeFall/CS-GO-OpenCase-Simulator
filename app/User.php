<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

//    public function weapons() {
//        return $this->belongsToMany('App\Weapon', 'item_user', 'user_id', 'item_id')->wherePivot('item_type', 'weapon')->withTimestamps();
//    }

//    public function rares() {
//        return $this->belongsToMany('App\Rare', 'item_user', 'user_id', 'item_id')->wherePivot('item_type', 'rare')->withTimestamps();
//    }

    public function items() {
        return $this->belongsToMany('App\Item', 'item_user', 'user_id', 'item_id')->withTimestamps();
//        return $this->belongsToMany('App\ItemUser');
    }

    public function weapons() {
        return $this->items()
            ->join('items_abstract', 'items_abstract.id', '=', 'items.item_abstract_id')
            ->where('items_abstract.rarity_id', '!=', '7');
    }

    public function rares() {
        return $this->items()
            ->join('items_abstract', 'items_abstract.id', '=', 'items.item_abstract_id')
            ->where('items_abstract.rarity_id', '=', '7');
    }

//    public function weapons() {
//        return $this->belongsToMany('App\Weapon', 'item_user')->withTimestamps();
//    }

    public function bets() {
        return $this->hasMany('App\RouletteBet');
    }

    public function getMoneyAttribute() {
//        return number_format($this->items()->sum('price'), 0, ',', '.');
        return number_format($this->items()->sum('price'), 2);
    }

    public function getMoneyDotsAttribute() {
        return number_format($this->money, 0, ',', '.');
    }

    public function getPointsDotsAttribute() {
        return number_format($this->points, 0, ',', '.');
    }

    public function casesLogs() {
        return $this->hasMany('App\ItemCaseLog');
    }

    public function rolls() {
        return $this->hasMany('App\RouletteBet');
    }

}
