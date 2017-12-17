@extends('appshell::layouts.default')

@section('title')
    {{ __('Viewing order :no', ['no' => $order->number]) }}
@stop

@section('content')

    @include('vanilo::order.show._cards')

    @include('vanilo::order.show._addresses')

    @include('vanilo::order.show._items')

    @include('vanilo::order.show._actions')

@stop
