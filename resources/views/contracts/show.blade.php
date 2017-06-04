@extends('layouts.app')
@push('styles')
    <link href="/css/tooltipster.bundle.min.css" rel="stylesheet">
    <link href="/css/tooltipster-sideTip-light.min.css" rel="stylesheet">
    <link href="/css/tooltipster-sideTip-borderless.min.css" rel="stylesheet">
@endpush
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h2 class="text-center">Contracts</h2>
                    </div>

                    <div class="panel-body">
                        <div class="row">
                            @include('users._contract_items')
                        </div>
                        <div class="row">
                            <button class="btn btn-success center-block" id="btn-contract" disabled>Contract!</button>
                        </div>
						<div class="row">
                            <button class="btn btn-default center-block" id="add-ten-items">Fill with first 10 items</button>
                        </div>
                        <br>
                        <div class="row text-center">
                            <button class="btn btn-info" id="contract-probability-btn" data-toggle="collapse" disabled data-target="#contract-probability">Show propabilities</button>
                            <div id="contract-probability" class="contract-probability collapse col-md-12">
                                <div id="contract-probability-inner" class="contract-probability-inner"></div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="inventory-items contract-inventory-items col-md-12" id="inventory-items">
                                @forelse($items as $item)
                                    @include('partials._item', ['abstract' => false, 'rare' => false, 'roulette' => false, 'itemClass' => 'inventory-item', 'disabled' => false, 'col' => 2])
                                @empty
                                    <div class="alert alert-warning">There are no {{ $rarity->title }} skins in your inventory at the moment!</div>
                                @endforelse
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('users._modal', ['action' => 'sell'])
<div class="tooltip_templates">
    <div id="tooltip-inventory-item">
        tooltip
    </div>
</div>
@endsection

@push('scripts')
    <script>
        var nextItemz = <?php echo $nextItemz; ?>
    </script>
        <script src="/js/tooltipster.bundle.min.js"></script>
        <script src="/js/basescripts.js"></script>
        <script src="/js/contract.js"></script>


@endpush
