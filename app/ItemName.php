<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ItemName extends Model
{
    protected $table = 'items_names';
    protected $fillable = ['item_type_id', 'title'];
    public $timestamps = false;

    public function items() {
        return $this->hasMany('App\ItemAbstract');
    }

    public function itemz() {
        return $this->hasManyThrough('App\Item', 'App\ItemAbstract');
    }

    public function topItemz() {
        //
    }

}
