@extends('layouts.app')
@push('styles')
    <link href="/css/tooltipster.bundle.min.css" rel="stylesheet">
    <link href="/css/tooltipster-sideTip-punk.min.css" rel="stylesheet">
@endpush
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h2 class="text-center">Cases logs</h2>
                </div>

                <div class="panel-body">
                    @forelse($logs as $log)
                        <div class="row col-md-6 case-log">
                                <div class="case-log-item-block">
                                    @php $item = $log->item @endphp
                                    @include('partials._item', ['abstract' => false, 'rare' => false, 'roulette' => false, 'itemClass' => 'inventory-item', 'disabled' => false, 'col' => 2])

                                    {{--{{ $log->item->short_title }}--}}
                                </div>
                                <div class="case-log-case-block">
                                    <img src="{{ asset('storage/images/cases/' . $log->itemCase->image) }}" alt="{{ $log->itemCase->title }}" class="case-log-case-image">
                                    {{ $log->itemCase->title }}
                                </div>
                                <div class="case-log-time">
                                    {{ $log->created_at }}
                                </div>
                        </div>
                        {{--<hr>--}}
                    @empty
                        <p>There are no cases logs at the moment.</p>
                    @endforelse
                    <div class="text-center">
                        {{ $logs->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
