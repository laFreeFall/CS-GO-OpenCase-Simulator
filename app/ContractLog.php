<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContractLog extends Model
{
    protected $table = 'contracts_logs';
    protected $fillable = ['user_id', 'received_item_id'];

    public function item() {
        return $this->belongsTo('App\Item', 'received_item_id');
    }

    public function items() {
        return $this->hasMany('App\ContractItem', 'contract_id', 'id');
    }

    public function itemz() {
        return $this->items()->items();
    }
}