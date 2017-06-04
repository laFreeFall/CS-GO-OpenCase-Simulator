<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CoinflipItem extends Model
{
    protected $table = 'coinflip_item';
    protected $fillable = ['coinflip_id', 'item_id', 'user_item'];
}