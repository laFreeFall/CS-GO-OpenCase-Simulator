@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h2 class="text-center">{{ $user->name }}`s Collector Progress</h2>
                    </div>
                    <div class="panel-body">
                        @forelse($cases as $case)
                            @php $caseCompleted = true; @endphp
                            <div class="row">
                                @forelse($case->coverts as $items)
                                    @foreach($items->childItems as $item)
                                        @unless($item->condition_id == $item->best_condition)
                                            @continue
                                        @endunless
                                        @include('partials._item', ['abstract' => false, 'rare' => false, 'roulette' => false, 'itemClass' => 'inventory-item', 'disabled' => !$item->has_user, 'col' => 2])
                                            @php if(! $item->hasUser) $caseCompleted = false; @endphp
                                    @endforeach
                                @empty
                                    <div class="alert alert-warning text-center">There are no available skins for progress from {{ $case->title }} at the moment.</div>
                                @endforelse
                                    <div class="progress-case pull-right {{ $caseCompleted ? '' : 'item-dizabled' }}">
                                        <i class="collector-case-icon fa {{ $caseCompleted ? 'fa-check collector-case-icon-yes' : 'fa-times collector-case-icon-no' }} fa-5x"></i>
                                        <div class="progress-case-image center-block">
                                            <a href="{{ action('CasesController@show', $case->id) }}"><img src="{{ asset('storage/images/cases/' . $case->image) }}" alt="{{ $case->title }}" class="progress-case-image"></a>
                                        </div>
                                        <div class="progress-case-title text-center">
                                            {{ $case->title }}
                                        </div>
                                    </div>
                            </div>
                        @empty
                            <div class="alert alert-warning text-center">There are no available progress at the moment.</div>
                        @endforelse
                            <hr>
                        @foreach($knivesGroupped as $knivesCollection)
                            <div class="progress-knife-block">
                                <img src="{{ asset('storage/images/itemz/' . $knives[$loop->index]->image) }}" alt="{{ $knives[$loop->index]->short_title }}" class="progress-knife-image center-block">
                                <h4 class="progress-knife-title text-center">{{ $knives[$loop->index]->weapon_title }}</h4>
                            </div>
                            <div class="row">
                                @foreach($knivesCollection as $item)

                                @php $knifeCompleted = true; @endphp
                                    @include('partials._item', ['abstract' => false, 'rare' => false, 'roulette' => false, 'itemClass' => 'inventory-item', 'disabled' => !$item->has_user, 'col' => 2])
                                    @php if(! $item->hasUser) $knifeCompleted = false; @endphp
                                @endforeach
                            </div>
                            <hr>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection