<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasManyThroughBelongsTo;

class ItemRarity extends Model
{
    protected $fillable = ['title', 'color', 'drop_rate', 'drop_sum_rate'];
    protected $table = 'items_rarities';
    public $timestamps = false;

//    public function item() {
//        return $this->belongsTo('App\Item');
//    }

    public function items() {
        return auth()->user()->weapons()->where('rarity_id', $this->id);
//        return $this->hasManyThrough('App\Item', 'App\ItemAbstract');
    }

    public function itemsCount($stattrak) {
        return $this->items()->where('stattrak', $stattrak)->count();
    }

//    public function weapons() {
//        return $this->hasManyThrough('App\Weapon', 'App\WeaponAbstract', 'rarity_id', 'item_abstract_id');
//    }

//    public function itemz() {
//        return $this->hasManyThroughBelongTo('App\ItemAbstract', 'App\ItemUser');
//    }

}