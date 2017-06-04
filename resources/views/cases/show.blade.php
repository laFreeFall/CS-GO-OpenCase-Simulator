@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h2 class="text-center" id="case-title" data-caseid="{{ $case->id }}">
                        {{ $case->title }}
                        @if(count($items))
                            <span class="badge">{{ $items->count() }}</span>
                        @endif
                    </h2>
                </div>

                <div class="panel-body">
                    @if(count($items))

                    <div class="items-roulette-window" id="items-roulette-window">
                        <div id="items-roulette-window-line"></div>
                        <div class="items-roulette-spinnable" id="items-roulette-spinnable">

                            <div id="items-roulette-spinnable-wrap">
                                @for ($i = 0; $i < 5; $i++)
                                    @if($case->collection)
                                        @include('partials._item', ['abstract' => true, 'collection' => true, 'rarity' => $items->last()->rarity->title, 'rare' => true, 'roulette' => true, 'rare_image' => $items->last()->image, 'rare_title_weapon' => $items->last()->weaponName->title, 'rare_title_pattern' => $items->last()->weaponPattern->title, 'disabled' => false, 'col' => 2])
                                    @else
                                        @include('partials._item', ['abstract' => true, 'rare' => true, 'roulette' => true, 'rare_image' => $case->rare_image, 'rare_title_weapon' => $case->rare_title, 'rare_title_pattern' => '&nbsp;', 'disabled' => false, 'col' => 2])
                                    @endif
                                @endfor
                            </div>
                        </div>
                        <div class="items-roulette-window-shadow" id="items-roulette-window-shadow"></div>
                        </div>
                    <div class="row">
                        <button class="btn btn-primary btn-open-case center-block" id="btn-open-case">
                            Open Case
                        </button><br>
                    </div>

                    @endif
                    @if(count($items))
                        <div class="row">
                        @foreach($items as $item)
                            @include('partials._item', ['abstract' => true, 'rare' => false, 'roulette' => false, 'disabled' => false, 'col' => 2])
                        @endforeach
                            @if($case->rare_image)
                                @include('partials._item', ['abstract' => true, 'rare' => true, 'roulette' => false, 'rare_image' => $case->rare_image,  'rare_title_weapon' => $case->rare_title, 'rare_title_pattern' => '&nbsp;',  'disabled' => false, 'col' => 2])
                            @endif
                        @else
                            <div class="alert alert-warning text-center">There are no available skins from {{ $case->title }} at the moment!</div>
                        @endif
                        </div>
                </div>

                </div>
            </div>
        </div>
    </div>
</div>
@include('users._modal', ['action' => 'sell'])
@endsection

@push('scripts')
<script src="http://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js"></script>
<script src="/js/jquery.animateNumber.min.js"></script>
<script src="/js/basescripts.js"></script>

<script src="/js/opencase.js"></script>
@endpush
