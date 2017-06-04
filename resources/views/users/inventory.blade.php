@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h2 class="text-center">Inventory
                            <span class="badge" id="inventory-user-items-amount">
                                {{ $items->total() }}
                            </span>
                            {{--<span class="label label-success inventory-total-price" id="inventory-user-total-price">--}}
{{--                                {{ auth()->user()->money }}$--}}
                            {{--</span>--}}
                        </h2>
                        <h4 class="text-center">Total price:
                            <span class="inventory-total-price" id="inventory-user-total-price">
                                {{ auth()->user()->money }}$
                            </span>
                        </h4>
                        <div class="row">
                        <a href="{{ action('UsersController@collector') }}" class="btn btn-info pull-right">Collector Progress</a>
                        </div>
                    </div>
{{--                    {{ dd($items) }}--}}
                    <div class="panel-body">
                        <div class="row">
                        {{--<div class="alert alert-warning">Here should be roulette with skins from the {{ $case->title }}</div>--}}
                            <br>
                            <div class="col-md-12 inventory-items">
                                @forelse($items as $item)
                                    @include('partials._item', ['abstract' => false, 'rare' => false, 'roulette' => false, 'itemClass' => 'inventory-item', 'disabled' => false, 'col' => 2])
                                @empty
                                    <div class="alert alert-warning">There are no skins in your inventory at the moment!</div>
                                @endforelse
                            </div>
                        </div>
                        <div class="text-center">
                            {{ $items->links() }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('users._modal', ['action' => 'sell'])

@endsection

@push('scripts')
<script src="/js/jquery.animateNumber.min.js"></script>
<script src="/js/basescripts.js"></script>
<script src="/js/inventory.js"></script>
@endpush
