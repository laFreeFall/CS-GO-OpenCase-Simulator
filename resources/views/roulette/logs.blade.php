@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h2 class="text-center">Roulette logs</h2>

                        <h4 class="text-center">
                            Total bets made:
                            {{ $rollsAll->count() }}
                            ({{ number_format($rollsAll->sum('value')) }}
                            <i class="fa fa-diamond"></i>)
                        </h4>

                        <h4 class="text-center">
                            Balance:
                            <span class="{{ $rollsAll->sum('profit') > 0 ? 'diamonds-roulette-green' : 'diamonds-roulette-red' }}">
                                {{ number_format($rollsAll->sum('profit')) }}
                                <i class="fa fa-diamond"></i>
                            </span>
                        </h4>

                        <h4 class="text-center">
                        Total bets won/lost:
                        <span class="diamonds-roulette-green">{{ $rollsAll->where('profit', '>', '0')->count() }}</span> /
                        <span class="diamonds-roulette-red">{{ $rollsAll->where('profit', '<', '0')->count() }}</span>
                        (<span class="diamonds-roulette-green">
                            {{ number_format($rollsAll->where('profit', '>', '0')->sum('profit')) }}
                            <i class="fa fa-diamond"></i>
                        </span>/
                        <span class="diamonds-roulette-red">
                            {{ number_format($rollsAll->where('profit', '<', '0')->sum('profit')) }}
                            <i class="fa fa-diamond"></i>
                        </span>)
                        </h4>

                        {{--<h4 class="text-center">--}}
                            {{--Total bets won:--}}
                            {{--<span class="diamonds-roulette-green">{{ $rolls->where('profit', '>', '0')->count() }}</span>--}}
                            {{--(<span class="diamonds-roulette-green">--}}
                                {{--{{ $rolls->where('profit', '>', '0')->sum('profit') }}--}}
                                {{--<i class="fa fa-diamond"></i>--}}
                            {{--</span>)--}}
                        {{--</h4>--}}

                        {{--<h4 class="text-center">--}}
                            {{--Total bets lost:--}}
                            {{--<span class="diamonds-roulette-red">{{ $rolls->where('profit', '<', '0')->count() }}</span>--}}
                            {{--(<span class="diamonds-roulette-red">{{ $rolls->where('profit', '<', '0')->sum('profit') }}--}}
                                {{--<i class="fa fa-diamond"></i>--}}
                            {{--</span>)--}}
                        {{--</h4>--}}

                        {{--<h4 class="text-center">Max bet: {{ $rolls->max('value') }}<i class="fa fa-diamond"></i></h4>--}}

                        <h4 class="text-center">
                            Max won bet/lost:
                            <span class="diamonds-roulette-green">
                            +{{ number_format($rollsAll->max('profit')) }}
                            <i class="fa fa-diamond"></i>
                            </span>/
                            <span class="diamonds-roulette-red">
                            {{ number_format($rollsAll->min('profit')) }}
                                <i class="fa fa-diamond"></i>
                            </span>
                        </h4>

                        {{--<h4 class="text-center">--}}
                            {{--Max won bet:--}}
                            {{--<span class="diamonds-roulette-green">--}}
                                {{--+{{ $rolls->max('profit') }}--}}
                                {{--<i class="fa fa-diamond"></i>--}}
                            {{--</span>--}}
                        {{--</h4>--}}

                        {{--<h4 class="text-center">--}}
                            {{--Max lost bet:--}}
                            {{--<span class="diamonds-roulette-red">--}}
                                {{--{{ $rolls->min('profit') }}--}}
                                {{--<i class="fa fa-diamond"></i>--}}
                            {{--</span>--}}
                        {{--</h4>--}}

                    </div>

                    <div class="panel-body">
                        <table class="table table-bordered">
                            <tr>
                                <th>ID</th>
                                <th>Time</th>
                                <th class="text-center">Amount</th>
                                <th class="text-center">Bet</th>
                                <th class="text-center">Roll</th>
                                <th class="text-center">Profit</th>
                            </tr>
                        @foreach($rolls as $roll)
                            <tr>
                                <td>
                                    {{ $roll->roll->id }}
                                </td>
                                <td>
                                    {{ $roll->created_at }}
                                </td>
                                <td class="text-center">
                                    {{ number_format($roll->value) }}
                                    <i class="fa fa-diamond"></i>
                                </td>
                                <td class="diamonds-roulette-inner-text diamonds-roulette-bg-{{ strtolower($roll->color->title) }}">
                                    {{ $roll->color->diapason_begin }}-{{ $roll->color->diapason_end }}
                                </td>
                                <td class="diamonds-roulette-inner-text diamonds-roulette-bg-{{ strtolower($roll->roll->color) }}">
                                    {{ $roll->roll->result }}
                                </td>
                                <td class="diamonds-roulette-inner-text {{ ($roll->profit > 0) ? 'diamonds-roulette-green' : 'diamonds-roulette-red' }}">
                                    {{ $roll->profit > 0 ? '+' : '' }}{{ number_format($roll->profit) }}
                                    <i class="fa fa-diamond"></i>
                                </td>
                            </tr>
                        @endforeach
                        </table>
                        <div class="text-center">
                            {{ $rolls->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

