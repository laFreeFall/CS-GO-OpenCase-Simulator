@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h2 class="text-center">Roulette</h2>
                        <div class="row">
                            <a href="{{ action('RouletteController@logs') }}" class="btn btn-info pull-right">Logs</a>
                        </div>
                    </div>

                    <div class="panel-body">
                        <div class="alert alert-info text-center" id="top-status">Waiting for bets...</div>
                        <div class="diamonds-roulette-window" id="diamonds-roulette-window">
                            <div id="diamonds-roulette-window-line"></div>
                            <div class="diamonds-roulette-spinnable" id="diamonds-roulette-spinnable">
                                <div id="diamonds-roulette-spinnable-wrap">
                                    @foreach($numbers as $number)
                                        <div class="diamonds-roulette-item diamonds-roulette-bg-{{ $number['color'] }}" id="diamonds-roulette-case-item-{{ $loop->index }}">
                                            <p class="diamonds-roulette-inner-text">{{ $number['value'] }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div id="diamonds-roulette-shadow"></div>
                        </div>
                       <div class="row text-center">
                           <div class="last-rolls" id="last-rolls">
                               @foreach($lastRolls as $roll)
                                   <div class="last-roll diamonds-roulette-bg-{{ $roll->color }} diamonds-roulette-inner-text">
                                       {{ $roll->result }}
                                   </div>
                               @endforeach
                           </div>
                       </div>
                        <div class="row text-center">
                            <div class="user-input" id="user-input">
                                <button class="btn btn-default" id="user-input-balance-block">Balance: <span id="user-input-balance">{{ auth()->user()->points }}</span> <span class="fa fa-diamond"></span></button>
                                <form class="form-inline">
                                    <div class="input-group">
                                        <input type="number" step="1" min="0" max="{{ auth()->user()->points }}" class="form-control" id="user-input-bet" placeholder="Bet amount...">
                                        <div class="input-group-addon"><span class="fa fa-diamond"></span></div>
                                    </div>
                                </form>
                                <button class="btn btn-default user-input-button" id="user-input-clear">Clear</button>
                                <button class="btn btn-default user-input-button" id="user-input-1">+1</button>
                                <button class="btn btn-default user-input-button" id="user-input-10">+10</button>
                                <button class="btn btn-default user-input-button" id="user-input-100">+100</button>
                                <button class="btn btn-default user-input-button" id="user-input-1000">+1000</button>
                                <button class="btn btn-default user-input-button" id="user-input-twice">x2</button>
                                <button class="btn btn-default user-input-button" id="user-input-half">1/2</button>
                                <button class="btn btn-default user-input-button" id="user-input-max">Max</button>
                            </div>
                        </div>
                        <button class="btn btn-success btn-open-case center-block" id="btn-roulette-spin">
                            Spin
                        </button>
                        <br>
                        <div class="row">
                            <div class="col-md-4 bets-col bets-col-red text-center">
                                <button class="btn bets-col-head diamonds-roulette-bg-red col-md-12">1 to 7</button>
                                <div><span class="roulette-won user-bet-sign" id="user-bet-red-sign"></span><span class="bets-col-own-bet" id="user-bet-red">0</span></div>
                                <div class="bets-col-total-bet">Total bet: <span class="bets-col-total-betq" id="bets-col-total-bet-red">0</span></div>
                            </div>
                            <div class="col-md-4 bets-col bets-col-green text-center">
                                <button class="btn bets-col-head diamonds-roulette-bg-green col-md-12">0</button>
                                <div><span class="roulette-won user-bet-sign" id="user-bet-green-sign"></span><span class="bets-col-own-bet" id="user-bet-green">0</span></div>
                                <div class="bets-col-total-bet">Total bet: <span class="bets-col-total-betq" id="bets-col-total-bet-green">0</span></div>
                            </div>
                            <div class="col-md-4 bets-col bets-col-black text-center">
                                <button class="btn bets-col-head diamonds-roulette-bg-black col-md-12">8 to 14</button>
                                <div><span class="roulette-won user-bet-sign" id="user-bet-black-sign"></span><span class="bets-col-own-bet" id="user-bet-black">0</span></div>
                                <div class="bets-col-total-bet">Total bet: <span class="bets-col-total-betq" id="bets-col-total-bet-black">0</span></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="myModal" role="dialog">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title text-center">Congratulations!</h4>
                </div>
                <div class="modal-body" id="myModal-body">
                    {{--<p class="text-center">{{ isset($rouletteItems) ? $rouletteItems[26]->weaponName->title : '' }} | {{ isset($rouletteItems) ? $rouletteItems[26]->weaponPattern->title : '' }}</p>--}}
                    {{--<img src="{{ asset('storage/images/items/' . isset($rouletteItems) ? $rouletteItems[26]->image : '') }}" alt="{{ isset($rouletteItems) ? $rouletteItems[26]->weaponName->title : '' }}" class="case-item-image center-block">--}}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">Sell for <span id="modal-item-price"></span><span class="fa fa-diamond"></span></button>
                    <button type="button" class="btn btn-success pull-right" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="http://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js"></script>
<script src="/js/jquery.animateNumber.min.js"></script>
<script src="/js/roulette-points.js"></script>

@endpush
