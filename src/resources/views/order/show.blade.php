@extends('appshell::layouts.private')

@section('title')
    {{ __('Viewing order :no', ['no' => $order->number]) }}
@stop

@section('content')

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

    @include('vanilo::order.show._actions')

@stop
