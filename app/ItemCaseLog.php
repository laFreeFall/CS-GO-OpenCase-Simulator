<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ItemCaseLog extends Model
{
    protected $table = 'cases_logs';
    protected $fillable = ['user_id', 'case_id', 'item_id'];

    public function user() {
        return $this->belongsTo('App\User');
    }

    public function itemCase() {
        return $this->belongsTo('App\ItemCase', 'case_id');
    }

    public function item() {
        return $this->belongsTo('App\Item');
    }
}