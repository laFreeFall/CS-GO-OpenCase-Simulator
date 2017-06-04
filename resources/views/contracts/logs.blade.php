@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h2 class="text-center">Contracts Logs</h2>
                    </div>

                    <div class="panel-body">
                        @forelse($contracts as $contract)
                        <div class="row contract-log">
                            <div class="contract-log-info">
                                <h4 class="text-center">
                                    Contract #{{ $contract->id }} |
                                    {{ $contract->created_at }}
                                </h4>
                            </div>
                            <div class="contract-log-user-items col-md-10">
                                @foreach($items[$contract->id] as $item)
                                    @include('partials._item', ['abstract' => false, 'rare' => false, 'roulette' => false, 'itemClass' => 'inventory-item', 'disabled' => false, 'col' => 5])
                                @endforeach
                            </div>
                            <div class="contract-log-received-item col-md-2">
                                @php $item = $contract->item @endphp
                                @include('partials._item', ['abstract' => false, 'rare' => false, 'roulette' => false, 'itemClass' => 'inventory-item', 'disabled' => false, 'col' => 2])
                            </div>
                        </div>
                            <hr>
                        @empty
                            <p>there are no contracts</p>
                        @endforelse
                        <div class="text-center">
                            {{ $contracts->links() }}
                        </div>
                            {{--<div class="contract-log row">--}}
                                {{--<div class="contract-log-info">--}}
                                    {{--{{ $contract->id }}--}}
                                    {{--{{ $contract->created_at     }}--}}
                                {{--</div>--}}
                            {{--@foreach($items[$contract->id] as $item)--}}
                                {{--@include('partials._item', ['abstract' => false, 'rare' => false, 'roulette' => false, 'itemClass' => 'inventory-item', 'disabled' => false])--}}
                            {{--@endforeach--}}
                                {{--@php $item = $contract->item @endphp--}}
                            {{--@include('partials._item', ['abstract' => false, 'rare' => false, 'roulette' => false, 'itemClass' => 'inventory-item', 'disabled' => false])--}}
                            {{--</div>--}}

                        {{--@empty--}}
                            {{--<p>there are no contracts</p>--}}
                        {{--@endforelse--}}
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

