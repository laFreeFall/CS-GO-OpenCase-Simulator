<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = ['item_abstract_id', 'condition_id', 'stattrak', 'souvenir', 'image', 'price'];
    public $timestamps = false;

    public function condition() {
        return $this->belongsTo('App\ItemCondition', 'condition_id');
    }

    public function baseItem() {
        return $this->belongsTo('App\ItemAbstract', 'item_abstract_id');
    }

    public function users() {
        return $this->belongsToMany('App\User', 'item_user')->withTimestamps();
    }

    public function getHasUserAttribute() {
        return $this->users->contains(auth()->user());
    }

    public function itemCase() {
//        return $this->baseItem->belongsToMany('App\ItemCase', 'case_item', 'item_abstract_id', 'case_id');
        return $this->baseItem->belongsToMany('App\ItemCase', 'case_item', 'item_abstract_id', 'case_id');
    }

    public function getWeaponTitleAttribute() {
//        return 'weapon_name';
        return $this->baseItem->weaponName->title;
    }

    public function getWeaponPatternAttribute() {
        return $this->baseItem->weaponPattern->title;
    }

    public function getShortTitleAttribute() {
//        $stattrak = $this->stattrak ? 'StatTrak&#8482; ' : '';
        return $this->weapon_title . ' | ' . $this->weapon_pattern;
    }

    public function getLongTitleAttribute() {
        $stattrak = $this->stattrak ? 'StatTrak&#8482; ' : '';
        return $stattrak . $this->weapon_title . ' | ' . $this->weapon_pattern . ' ' . $this->condition->title;
    }

    public function getBestConditionAttribute() {
        return $this->baseItem->best_condition;
    }

    public function getConditionAbbrAttribute() {
        switch($this->condition->title) {
            case 'Factory New': $abbr = 'FN'; break;
            case 'Minimal Wear': $abbr = 'MW'; break;
            case 'Field-Tested': $abbr = 'FT'; break;
            case 'Well-Worn': $abbr = 'WW'; break;
            case 'Battle-Scarred': $abbr = 'BS'; break;
            default: $abbr= 'ERR';
        }
        return $abbr;
    }

    public function getMiddleTitleAttribute() {
        $stattrak = $this->stattrak ? 'ST&#8482; ' : '';
        return $stattrak . $this->weapon_title . ' | ' . $this->weapon_pattern . ' ' . $this->getConditionAbbrAttribute();
    }

    public function getRarityClassAttribute() {
        return strtolower($this->baseItem->rarity->title);
    }
}
