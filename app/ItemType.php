<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ItemType extends Model
{
    protected $table = 'items_types';
    protected $fillable = ['title'];
    public $timestamps = false;

    public function items() {
        return $this->belongsToMany('App\Item');
    }

}
