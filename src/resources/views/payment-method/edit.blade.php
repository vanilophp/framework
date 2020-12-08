@extends('appshell::layouts.private')

@section('title')
    {{ __('Editing') }} {{ $paymentMethod->name }}
@stop

@section('content')
{!! Form::model($paymentMethod, [
        'route'  => ['vanilo.payment-method.update', $paymentMethod],
        'method' => 'PUT'
    ])
!!}

    <div class="card card-accent-secondary">
        <div class="card-header">
            {{ __('Payment Method Details') }}
        </div>

        <div class="card-body">
            @include('vanilo::payment-method._form')
        </div>

        <div class="card-footer">
            <button class="btn btn-primary">{{ __('Save') }}</button>
            <a href="#" onclick="history.back();" class="btn btn-link text-muted">{{ __('Cancel') }}</a>
        </div>
    </div>

{!! Form::close() !!}
@stop
