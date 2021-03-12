@extends('appshell::layouts.print')

@section('title')
    {{ __('Order :no', ['no' => $order->number]) }}
@stop

@section('content')

    <h1>@yield('title')</h1>

    <div class="card-deck mb-3">
        @include('vanilo::order.show._cards')
    </div>

    <div class="card-deck mb-3">
        @include('vanilo::order.show._addresses')
        @include('vanilo::order.show._details')
    </div>

    <div class="row">

        <div class="col-12 col-md-8">
            @include('vanilo::order.show._items')
        </div>

        <div class="col-12 col-md-4">
            @include('vanilo::order.show._payments')
        </div>
    </div>
@stop
