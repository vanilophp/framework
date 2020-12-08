@extends('appshell::layouts.private')

@section('title')
    {{ __('Create Payment Method') }}
@stop

@section('content')
{!! Form::model($paymentMethod, ['route' => 'vanilo.payment-method.store', 'autocomplete' => 'off']) !!}

    <div class="card card-accent-success">

        <div class="card-header">
            {{ __('Payment Method Details') }}
        </div>

        <div class="card-body">
            @include('vanilo::payment-method._form')
        </div>

        <div class="card-footer">
            <button class="btn btn-success">{{ __('Create payment method') }}</button>
            <a href="#" onclick="history.back();" class="btn btn-link text-muted">{{ __('Cancel') }}</a>
        </div>
    </div>

{!! Form::close() !!}
@stop
