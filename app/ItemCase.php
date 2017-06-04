<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ItemCase extends Model
{
    protected $table = 'items_cases';

    protected $fillable = ['title', 'description', 'image', 'price'];


    public function items() {
        return $this->belongsToMany('App\ItemAbstract', 'case_item', 'case_id', 'item_abstract_id');
    }

//    public function itemz() {
//        return $this->hasManyThrough('App\Item', 'App\ItemAbstract', );
//    }

    public function weapons() {
        return $this->items()->where('rarity_id', '!=', '7')->with('childItems');
    }

    public function coverts() {
        return $this->items()->where('rarity_id', '6')->with('childItems');
    }

    public function topCoverts() {
        return $this->items()->bestChildItems;
    }

    public function weaponz() {
//        return $this->hasManyThrough('App\Item', 'App\ItemAbstract', 'item_id', 'item_abstract_id');
        return $this->weapons()->with('childItems');
    }

    public function rares() {
        return $this->items()->where('rarity_id', '=', '7');
    }


}