@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h2 class="text-center">Coinflip Logs</h2>
                    </div>

                    <div class="panel-body">
                        @forelse($coinflips as $coinflip)
                        <div class="row coinflip-log">
                            <div class="coinflip-log-info">
                                <h4 class="text-center">
                                    Coinflip #{{ $coinflip->id }} |
                                    {{ $coinflip->created_at }}
                                    {{ $coinflip->victory ? 'Won' : 'Lost' }}
                                </h4>
                            </div>
                            <div class="coinflip-log-user-info col-md-2">
                                <div class="avatar avatar-block">
                                    <img src="{{ asset('storage/images/avatars/' . auth()->user()->avatar) }}" alt="{{  auth()->user()->name }}" class="coinflip-avatar center-block">
                                    <img src="{{ asset($coinflip->side ?  'storage/images/coinflip/t.png' : 'storage/images/coinflip/ct.png') }}" alt="CT" class="center-block coinflip-side-image coinflip-side-image-log coinflip-side-image-ct coinflip-side-image-user">
                                </div>
                                <h3 class="text-center"> {{ auth()->user()->name }}</h3>
                                @if($coinflip->victory)
                                    <h4 class="text-center diamonds-roulette-green">Won <span id="coinflip-user-items-amount">{{ $items[$coinflip->id]->where('user_item', '0')->count() }}</span> <span id="user-items-word">{{ $items[$coinflip->id]->where('user_item', '0')->count() == 1 ? 'item' : 'items' }}</span></h4>
                                    <h4 class="text-center diamonds-roulette-green" id="user-total-sum">$ <span id="coinflip-user-sum">{{ $items[$coinflip->id]->where('user_item', '0')->sum('price') }}</span> (<span class="user-percentage" id="user-percentage">{{ number_format(($items[$coinflip->id]->where('user_item', '1')->sum('price')) / (($items[$coinflip->id]->where('user_item', '1')->sum('price')) + $items[$coinflip->id]->where('user_item', '0')->sum('price')), 4) * 100 }}</span>%)</h4>
                                </div>
                                <div class="coinflip-log-user-items col-md-10">
                                    @foreach($items[$coinflip->id]->where('user_item', '0') as $item)
                                        @include('partials._item', ['abstract' => false, 'rare' => false, 'roulette' => false, 'itemClass' => 'inventory-item', 'disabled' => false, 'col' => 5])
                                    @endforeach
                                </div>
                                @else
                                <h4 class="text-center diamonds-roulette-red">Lost <span id="coinflip-user-items-amount">{{ $items[$coinflip->id]->where('user_item', '1')->count() }}</span> <span id="user-items-word">{{ $items[$coinflip->id]->where('user_item', '1')->count() == 1 ? 'item' : 'items' }}</span></h4>
                                <h4 class="text-center diamonds-roulette-red" id="user-total-sum">$ <span id="coinflip-user-sum">{{ $items[$coinflip->id]->where('user_item', '1')->sum('price') }}</span> (<span class="user-percentage" id="user-percentage">{{ number_format(($items[$coinflip->id]->where('user_item', '1')->sum('price')) / (($items[$coinflip->id]->where('user_item', '1')->sum('price')) + $items[$coinflip->id]->where('user_item', '0')->sum('price')), 4) * 100 }}</span>%)</h4>
                                </div>
                                <div class="coinflip-log-user-items col-md-10">
                                    @foreach($items[$coinflip->id]->where('user_item', '1') as $item)
                                        @include('partials._item', ['abstract' => false, 'rare' => false, 'roulette' => false, 'itemClass' => 'inventory-item', 'disabled' => true, 'col' => 5])
                                    @endforeach
                                </div>
                                @endif
                            <button class="btn btn-default pull-right" data-toggle="collapse" data-target="#coinflip-{{ $coinflip->id }}-info">Additional info</button>

                        </div>
                    <br>
                    <div id="coinflip-{{ $coinflip->id }}-info" class="row collapse">
                        @if($coinflip->victory)
                        <div class="coinflip-log-user-info col-md-2">
                            <div class="avatar avatar-block">
                                <img src="{{ asset('storage/images/avatars/' . auth()->user()->avatar) }}" alt="{{ auth()->user()->name }}" class="coinflip-avatar center-block">
                                <img src="{{ asset($coinflip->side ?  'storage/images/coinflip/t.png' : 'storage/images/coinflip/ct.png') }}" alt="CT" class="center-block coinflip-side-image coinflip-side-image-log coinflip-side-image-ct coinflip-side-image-user">
                            </div>
                            <h3 class="text-center"> {{ auth()->user()->name }}</h3>
                                <h4 class="text-center diamonds-roulette-green">Bet <span id="coinflip-user-items-amount">{{ $items[$coinflip->id]->where('user_item', '1')->count() }}</span> <span id="user-items-word">{{ $items[$coinflip->id]->where('user_item', '1')->count() == 1 ? 'item' : 'items' }}</span></h4>
                                <h4 class="text-center diamonds-roulette-green" id="user-total-sum">$ <span id="coinflip-user-sum">{{ $items[$coinflip->id]->where('user_item', '1')->sum('price') }}</span> (<span class="user-percentage" id="user-percentage">{{ number_format(($items[$coinflip->id]->where('user_item', '1')->sum('price')) / (($items[$coinflip->id]->where('user_item', '1')->sum('price')) + $items[$coinflip->id]->where('user_item', '0')->sum('price')), 4) * 100 }}</span>%)</h4>
                        </div>
                        <div class="coinflip-log-user-items col-md-10">
                            @foreach($items[$coinflip->id]->where('user_item', '1') as $item)
                                @include('partials._item', ['abstract' => false, 'rare' => false, 'roulette' => false, 'itemClass' => 'inventory-item', 'disabled' => false, 'col' => 5])
                            @endforeach
                        </div>
                        @else
                            <div class="coinflip-log-user-info col-md-2">
                                <div class="avatar avatar-block">
                                    <img src="{{ asset('storage/images/avatars/' . $coinflip->bot->avatar) }}" alt="{{  $coinflip->bot->name }}" class="coinflip-avatar center-block">
                                    <img src="{{ asset($coinflip->side ?  'storage/images/coinflip/t.png' : 'storage/images/coinflip/ct.png') }}" alt="CT" class="center-block coinflip-side-image coinflip-side-image-log coinflip-side-image-ct coinflip-side-image-user">
                                </div>
                                <h3 class="text-center"> {{ $coinflip->bot->name }}</h3>
                                <h4 class="text-center diamonds-roulette-green">Bet <span id="coinflip-user-items-amount">{{ $items[$coinflip->id]->where('user_item', '0')->count() }}</span> <span id="user-items-word">{{ $items[$coinflip->id]->where('user_item', '0')->count() == 1 ? 'item' : 'items' }}</span></h4>
                                <h4 class="text-center diamonds-roulette-green" id="user-total-sum">$ <span id="coinflip-user-sum">{{ $items[$coinflip->id]->where('user_item', '0')->sum('price') }}</span> (<span class="user-percentage" id="user-percentage">{{ number_format(($items[$coinflip->id]->where('user_item', '0')->sum('price')) / (($items[$coinflip->id]->where('user_item', '1')->sum('price')) + $items[$coinflip->id]->where('user_item', '0')->sum('price')), 4) * 100 }}</span>%)</h4>
                            </div>
                            <div class="coinflip-log-user-items col-md-10">
                                @foreach($items[$coinflip->id]->where('user_item', '0') as $item)
                                    @include('partials._item', ['abstract' => false, 'rare' => false, 'roulette' => false, 'itemClass' => 'inventory-item', 'disabled' => false, 'col' => 5])
                                @endforeach
                            </div>
                        @endif
                        </div>
                            <hr>
                        @empty
                            <p>There are no coinflips games yet.</p>
                        @endforelse
                    </div>
                <div class="row text-center">
                    {{ $coinflips->links() }}
                </div>
                </div>
            </div>
        </div>
    </div>

@endsection

