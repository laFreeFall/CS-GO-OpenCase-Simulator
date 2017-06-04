@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h2 class="text-center">{{ $user->name }}`s stats</h2>
                    </div>
                    <div class="panel-body">
                        <h3 class="user-stat text-center">Cases <a href="{{ action('CasesController@logs') }}">[logs]</a></h3>
                        <ul class="list-group">
                            <li class="list-group-item">Total cases opened: {{ $stats['cases']['totalCasesOpened'] }}</li>
                            <li class="list-group-item">Total money wasted: 2$</li>
                            <li class="list-group-item">Total money earned: 3$</li>
                            <li class="list-group-item">Total knives earned: 3$</li>
                            <li class="list-group-item">Total red earned: 3$</li>
                            <li class="list-group-item">Total purple earned: 3$</li>
                            <li class="list-group-item">Total violet earned: 3$</li>
                            <li class="list-group-item">Total blue earned: 3$</li>
                            <li class="list-group-item">Total ST earned: 3$</li>
                            <li class="list-group-item">Total souvenir earned: 3$</li>
                            <li class="list-group-item">Total FN earned: 3$</li>
                            <li class="list-group-item">Total MW earned: 3$</li>
                            <li class="list-group-item">Total FT earned: 3$</li>
                            <li class="list-group-item">Total WW earned: 3$</li>
                            <li class="list-group-item">Total BS earned: 3$</li>
                        </ul>

                        <h3 class="user-stat text-center">Roulette Total</h3>
                        <ul class="list-group">
                            <li class="list-group-item">Total spins made: 1</li>
                            <li class="list-group-item">Total spins won: 2 (74%)</li>
                            <li class="list-group-item">Total money won: 2$</li>
                            <li class="list-group-item">Total spins lost: 3</li>
                            <li class="list-group-item">Total money lost: 3$ (26(%)</li>
                            <li class="list-group-item">Max win streak: 3$ (26(%)</li>
                            <li class="list-group-item">Max lose streak: 3$ (26(%)</li>
                        </ul>

                        <h3 class="user-stat text-center">Roulette Red</h3>
                        <ul class="list-group">
                            <li class="list-group-item">Total spins made: 1</li>
                            <li class="list-group-item">Total spins won: 2 (74%)</li>
                            <li class="list-group-item">Total money won: 2$</li>
                            <li class="list-group-item">Total spins lost: 3</li>
                            <li class="list-group-item">Total money lost: 3$ (26(%)</li>
                            <li class="list-group-item">Max win streak: 3$ (26(%)</li>
                            <li class="list-group-item">Max lose streak: 3$ (26(%)</li>
                        </ul>

                        <h3 class="user-stat text-center">Roulette Black</h3>
                        <ul class="list-group">
                            <li class="list-group-item">Total spins made: 1</li>
                            <li class="list-group-item">Total spins won: 2 (74%)</li>
                            <li class="list-group-item">Total money won: 2$</li>
                            <li class="list-group-item">Total spins lost: 3</li>
                            <li class="list-group-item">Total money lost: 3$ (26(%)</li>
                            <li class="list-group-item">Max win streak: 3$ (26(%)</li>
                            <li class="list-group-item">Max lose streak: 3$ (26(%)</li>
                        </ul>

                        <h3 class="user-stat text-center">Roulette Green</h3>
                        <ul class="list-group">
                            <li class="list-group-item">Total spins made: 1</li>
                            <li class="list-group-item">Total spins won: 2 (74%)</li>
                            <li class="list-group-item">Total money won: 2$</li>
                            <li class="list-group-item">Total spins lost: 3</li>
                            <li class="list-group-item">Total money lost: 3$ (26(%)</li>
                            <li class="list-group-item">Max win streak: 3$ (26(%)</li>
                            <li class="list-group-item">Max lose streak: 3$ (26(%)</li>
                        </ul>

                        <h3 class="user-stat text-center">Contracts</h3>
                        <ul class="list-group">
                            <li class="list-group-item">Total contracts made: 1</li>
                            <li class="list-group-item">Total red contracts made: 1</li>
                            <li class="list-group-item">Total purple contracts made: 1</li>
                            <li class="list-group-item">Total violet contracts made: 1</li>
                            <li class="list-group-item">Total blue contracts made: 1</li>
                        </ul>

                        <h3 class="user-stat text-center">Coinflip</h3>
                        <ul class="list-group">
                            <li class="list-group-item">Total coins flipped: 1</li>
                            <li class="list-group-item">Games won: 1</li>
                            <li class="list-group-item">Money won: 1$</li>
                            <li class="list-group-item">Games lost: 1</li>
                            <li class="list-group-item">Money lost: 1$</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection