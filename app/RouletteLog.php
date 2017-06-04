<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RouletteLog extends Model
{
    protected $table = 'roulette_logs';
    protected $fillable = ['result'];

    public function getColorAttribute() {
        if(($this->result >= 1) && ($this->result <= 7)) { // RED
            $color = 'red';
        }
        elseif(($this->result >= 8) && ($this->result <= 14)) { // BLACK
            $color = 'black';
        }
        else { // GREEN
            $color = 'green';
        }

        return $color;
    }
}