@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h2 class="text-center">Contracts</h2>
                        <div class="row">
                            <a href="{{ action('ContractsController@logs') }}" class="btn btn-info pull-right">Logs</a>
                        </div>
                    </div>

                    <div class="panel-body">
                        <div class="rarities">
                        @foreach($rarities as $rarity)
                            <div class="col-md-5ths">
                                <div class="rarity row text-center">
                                    <h4 class="modal-star-bg-{{ strtolower($rarity->title) }}">
                                        <a class="modal-star-bg-{{ strtolower($rarity->title) }}" href="{{ action('ContractsController@show', $rarity->id) }}"> {{ $rarity->title }}</a>
{{--                                        @if($rarity->itemsCount(false) > 0)--}}
                                            <span class="badge">{{ $rarity->itemsCount(false) }}</span>
                                        {{--@endif--}}
                                    </h4>
                                </div>
                                <div class="rarity row text-center">
                                    <h4 class="modal-star-bg-{{ strtolower($rarity->title) }}">
                                        <a class="modal-star-bg-{{ strtolower($rarity->title) }}" href="{{ action('ContractsController@show', $rarity->id) }}/stattrak"> {{ $rarity->title }} Stattrak&#8482;</a>
{{--                                        @if($rarity->itemsCount(true) > 0)--}}
                                            <span class="badge">{{ $rarity->itemsCount(true) }}</span>
                                        {{--@endif--}}
                                    </h4>
                                </div>
                                {{--<div class="rarity row">--}}
                                    {{--<div class="col-md-2 rarity">--}}
                                        {{--<h4 class="modal-star-bg-{{ strtolower($rarity->title) }}">--}}
                                            {{--<a class="modal-star-bg-{{ strtolower($rarity->title) }}" href="{{ action('ContractsController@show', $rarity->id) }}/souvenir"> {{ $rarity->title }} Souvenir</a>--}}
                                        {{--</h4>--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                            </div>

                        @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')

@endpush
