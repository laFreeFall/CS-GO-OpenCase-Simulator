<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    protected $table = 'inventory';
    protected $fillable = ['user_id', 'item_id'];

    public function user() {
        return $this->belongsTo('App\User');
    }

    public function item() {
        return $this->hasOne('App\Item');
    }

    public function items() {
        return $this->belongsToMany('App\ItemAbstract', 'item_id');
    }
}