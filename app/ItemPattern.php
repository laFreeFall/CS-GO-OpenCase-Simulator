<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ItemPattern extends Model
{
    protected $table = 'items_patterns';
    protected $fillable = ['title'];
    public $timestamps = false;

    public function items() {
        return $this->belongsToMany('App\ItemAbstract');
    }

    public function weapons() {
        return $this->belongsToMany('App\WeaponAbstract');
    }
}
