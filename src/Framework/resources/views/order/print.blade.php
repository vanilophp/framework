@extends('appshell::layouts.print')

@section('title')
    {{ __('Order :no', ['no' => $order->number]) }}
@stop

@section('content')

    <h1>@yield('title')</h1>


    @include('vanilo::order.print._header')

    <h3>{{ __('Customer Notes') }}</h3>

    <div class="font-italic">
        @isset($order->notes)
            {!! nl2br($order->notes) !!}
        @else
            {{ __('No special notes have been added by the customer') }}
        @endif
    </div>

    @include('vanilo::order.print._items')

    @include('vanilo::order.print._payment')

@stop
