@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h2 class="text-center">Coin Flip</h2>
                        <div class="row">
                            <a href="{{ action('CoinflipController@logs') }}" class="btn btn-info pull-right">Logs</a>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-4" id="coinflip-main-user">
                                <div class="avatar avatar-block">
                                <img src="{{ asset('storage/images/avatars/' . auth()->user()->avatar) }}" alt="{{  auth()->user()->name }}" class="coinflip-avatar center-block">
                                <img src="{{ asset('storage/images/coinflip/ct.png') }}" alt="CT" class="center-block coinflip-side-image coinflip-side-image-ct coinflip-side-image-user">
                            </div>
                            <h3 class="text-center"> {{ auth()->user()->name }}</h3>
                            <h4 class="text-center"><span id="coinflip-user-items-amount">0</span> <span id="user-items-word">items</span></h4>
                            <h4 class="text-center" id="user-total-sum">$ <span id="coinflip-user-sum">0</span> (<span class="user-percentage" id="user-percentage">100</span>%)</h4>

                        </div>
                            <div class="col-md-4">
                                <div class="coinflip-coin-container">
                                    <div class="coinflip-coin-block" id="coinflip-coin-block">
                                        <img src="{{ asset('storage/images/coinflip/ct.png') }}" alt="" class="center-block coinflip-coin coinflip-coin-ct front" id="coinflip-coin-ct">
                                        <img src="{{ asset('storage/images/coinflip/t.png') }}" alt="" class="center-block coinflip-coin coinflip-coin-t back" id="coinflip-coin-t" >
                                        <img src="{{ asset('storage/images/coinflip/mix.png') }}" alt="" class="center-block coinflip-coin front" id="coinflip-coin-mix">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4" id="coinflip-main-enemy">
                                <div class="avatar avatar-block">
                                    <img src="{{ asset('storage/images/avatars/no_avatar.png') }}" alt="No Player" class="coinflip-avatar center-block" id="enemy-avatar">
                                    <img src="{{ asset('storage/images/coinflip/t.png') }}" alt="T" class="center-block coinflip-side-image coinflip-side-image-t coinflip-side-image-enemy">
                                    <div class="cssload-container" id="cssload">
                                        <div class="cssload-loading"><i></i><i></i></div>
                                    </div>
                                </div>
                                <h3 class="text-center" id="enemy-username">No Player</h3>
                                <h4 class="text-center"><span id="coinflip-enemy-items-amount">0</span> <span id="enemy-items-word">items</span></h4>
                                <h4 class="text-center">$ <span id="enemy-money">0</span> (<span class="enemy-percentage" id="enemy-percentage">0</span>%)</h4>

                            </div>
                        </div>
                        <div class="row" id="contract-items-block">
                            <hr>
                            <h3 class="text-center"><span id="user-items-amount">0</span>/10 items (<span id="user-items-price">0</span>$)</h3>
                            @include('users._contract_items')
                            <div class="clearfix"></div>
                            <button class="btn btn-primary center-block" id="btn-contract" disabled>Flip!</button>
                            <hr>
                            <div class="row">
                                <div class="inventory-items contract-inventory-items col-md-10 col-md-offset-1" id="inventory-items">
                                    @forelse($items as $item)
                                        @include('partials._item', ['abstract' => false, 'rare' => false, 'roulette' => false, 'itemClass' => 'inventory-item', 'disabled' => false, 'col' => 2])
                                    @empty
                                        <div class="alert alert-warning">There are no available skins in your inventory at the moment!</div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                        <div class="row text-center">
                            <input type="button" class="btn btn-info center-block" id="btn-try-again" value="Try again?" onClick="window.location.reload()">
                        </div>
                </div>

            </div>
        </div>
    </div>
    <div class="modal fade" id="myModal" role="dialog">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header" id="coinflip-modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title text-center" id="coinflip-modal-title">Congratulations!</h4>
                </div>
                <div class="modal-body" id="myModal-body">
                    <h4 class="text-center" id="coinflip-modal-sum"></h4>
                    <h5 class="text-center" id="coinflip-modal-items"></h5>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="/js/jquery.animateNumber.min.js"></script>
    {{--<script src="/js/jquery.flip.min.js"></script>--}}
    {{--<script src="/js/jquery.transit.min.js"></script>--}}
    <script src="/js/basescripts.js"></script>
    <script src="/js/coinflip.js"></script>
@endpush
