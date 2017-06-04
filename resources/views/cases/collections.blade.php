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
                    <h2 class="text-center">Collections <span class="badge">{{ $collections->count() }}</span></h2>
                </div>

                <div class="panel-body">
                    @forelse($collections as $collection)
                        @include('cases._case', ['case' => $collection])
                    @empty
                        <p class="warning">There are no available collections at the moment!</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script src="/js/tooltipster.bundle.min.js"></script>
<script>
    $(document).ready(function() {
        $('.tooltipq').tooltipster({
            theme: 'tooltipster-punk'
        });
    });
</script>
@endpush
