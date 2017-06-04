@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h2 class="text-center">
                        {{ $case->title }} Shop
{{--                    @if(count($items))--}}
                            {{--<span class="badge">{{ $items->count() }}</span>--}}
                        {{--@endif--}}
                    </h2>
                </div>

                <div class="panel-body">
                    @foreach($itemsGroups as $itemGroup)
                        <div class="row">
                        <button data-toggle="collapse"
                            data-target="#shop-items-list-{{ $loop->iteration }}"
                            class="btn btn-default btn-shop-spoiler shop-item col-md-8 col-md-offset-2  item-background-{{ strtolower($itemGroup[0]->baseItem->rarity->title) }}"
                        >
                            {{ $itemGroup[0]->short_title }}
                        </button>
                        <ul class="list-group col-md-8 col-md-offset-2 collapse" id="shop-items-list-{{ $loop->iteration }}">
                            @foreach($itemGroup as $item)
                                <li class="list-group-item shop-item shop-item-inner item-background-{{ strtolower($item->baseItem->rarity->title) }}"
                                    data-img="{{ asset('storage/images/itemz/' . $item->image) }}"
                                    data-itemid="{{ $item->id }}"
                                >
                                    <span class="shop-item-text">
                                        <span class="shop-item-title">
                                            {{ $item->long_title }}
                                        </span>
                                        <button class="btn btn-default btn-sm shop-item-price pull-right" {{ $item->condition->id == min($item->baseItem->conditions) ? 'disabled' : '' }}>
{{--                                            {{ number_format($item->price, 2) }}$--}}
                                            {{ $item->price }}$
                                        </button>
                                        <span class="clearfix"></span>
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@include('users._modal_confirm', ['action' => 'buy'])
@endsection

@push('scripts')
<script src="/js/jquery.animateNumber.min.js"></script>

<script src="/js/shop.js"></script>
@endpush
