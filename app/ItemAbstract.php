<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ItemAbstract extends Model
{
    protected $table = 'items_abstract';
    protected $fillable = ['item_name_id', 'item_pattern_id', 'rarity_id', 'image'];
    public $timestamps = false;

    public function rarity() {
        return $this->belongsTo('App\ItemRarity', 'rarity_id');
    }

    public function icase() {
//        return $this->belongsTo('App\ItemCase', 'case_id');
        return $this->belongsToMany('App\ItemCase', 'case_item', 'item_abstract_id', 'case_id');
    }

    public function weaponName() {
        return $this->belongsTo('App\ItemName', 'item_name_id');
    }

    public function weaponPattern() {
        return $this->belongsTo('App\ItemPattern', 'item_pattern_id');
    }

    public function childItems() {
        return $this->hasMany('App\Item');
    }

    public function getConditionsAttribute() {
        return array_unique($this->childItems->pluck('condition_id')->toArray());
    }

    public function getBestConditionAttribute() {
        return $this->childItems->pluck('condition_id')->unique()->values()->min();
    }

    public function getBestChildItemsAttribute() {
        return $this->childItems->where('condition_id', $this->best_condition);
    }

    public function bestChildItems() {
        return $this->childItems()->where('condition_id', $this->best_condition);
    }

}
