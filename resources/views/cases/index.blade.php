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
                    <h2 class="text-center">Cases <span class="badge">{{ $cases->count() }}</span></h2>
                </div>

                <div class="panel-body">
                    @forelse($cases as $case)
                        @include('cases._case', ['case' => $case])
                    @empty
                        <p class="warning">There are no available case at the moment!</p>
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
