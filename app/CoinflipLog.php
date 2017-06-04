<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CoinflipLog extends Model
{
    protected $table = 'coinflip_logs';
    protected $fillable = ['user_id', 'enemy_id', 'side', 'victory'];

    public function bot() {
        return $this->belongsTo('App\Bot', 'enemy_id');
    }
}