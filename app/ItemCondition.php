<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ItemCondition extends Model
{
    protected $fillable = ['title', 'short_title', 'drop_rate'];
    protected $table = 'items_conditions';
    public $timestamps = false;

    public function item() {
        return $this->belongsTo('App\Item');
    }

    public function weapon() {
        return $this->belongsTo('App\Weapon');
    }
}