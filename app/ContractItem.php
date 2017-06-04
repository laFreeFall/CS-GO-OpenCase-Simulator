<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContractItem extends Model
{
    protected $table = 'contract_item';
    protected $fillable = ['contract_id', 'user_item_id'];

    public function item() {
        return $this->belongsTo('App\Item', 'user_item_id');
    }
}