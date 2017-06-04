<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RouletteColor extends Model
{
    protected $fillable = ['title', 'color', 'diapason_begin', 'diapason_end', 'multilplier'];
    public $timestamps = false;

}
