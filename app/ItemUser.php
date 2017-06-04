<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ItemUser extends Model
{
    protected $table = 'item_user';
    protected $fillable = ['user_id', 'item_id', 'item_type'];

    public function users() {
        return $this->belongsToMany('App\User', 'user_id');
    }

    public function items() {
        return $this->belongsToMany('App\Weapon', 'item_id');
    }
}