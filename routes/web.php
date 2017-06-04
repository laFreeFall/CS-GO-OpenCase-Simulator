<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/', 'UsersController@stats');

Route::get('cases', 'CasesController@index');
Route::get('collections', 'CasesController@collections');
Route::group(['middleware' => 'auth'], function () {
    Route::get('case/{case}', 'CasesController@show');
    Route::get('cases/logs', 'CasesController@logs');
    Route::get('inventory', 'UsersController@inventory');
    Route::get('roulette', 'RouletteController@index');
    Route::get('roulette/logs', 'RouletteController@logs');

    Route::get('contracts', 'ContractsController@index');
    Route::get('contract/{rarity}/{stattrak?}', 'ContractsController@show');
    Route::post('contract/exchange', 'ContractsController@exchange');
    Route::post('contract/collection', 'ContractsController@collection');
    Route::get('contracts/logs', 'ContractsController@logs');
    Route::get('case/{case}/shop', 'CasesController@shop');

    Route::get('coinflip', 'CoinflipController@index');
    Route::get('coinflip/logs', 'CoinflipController@logs');

    Route::get('collector', 'UsersController@collector');

    Route::post('case/open', 'CasesController@getRouletteItems');
    Route::post('sell-item', 'UsersController@sellItem');
    Route::post('get-roulette-numbers', 'RouletteController@getRouletteNumbers');
    Route::post('buy-shop-item', 'CasesController@buyShopItem');
    Route::post('coinflip/flip', 'CoinflipController@flip');
});



Route::get('test', 'HomeController@test');
Route::get('test/{case}', 'HomeController@testCase');