@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="text-center">Home start page</h3>
                </div>

                <div class="panel-body">
                    @forelse($items as $item)
                        @include('partials._item')
                    @empty
                        <div class="alert alert-warning">There are no available skins at the moment!</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
