@extends('appshell::layouts.private')

@section('title')
    {{ $paymentMethod->getName() }}
@stop

@section('content')

    <div class="card mb-3">
        <div class="card-header">
            <h5>{{ $paymentMethod->getName() }}</h5>
        </div>
        <div class="card-body">

        </div>
    </div>

    <div class="card">
        <div class="card-body">
            @can('edit payment methods')
                <a href="{{ route('vanilo.payment-method.edit', $paymentMethod) }}" class="btn btn-outline-primary">{{ __('Edit Payment Method') }}</a>
            @endcan

            @can('delete payment methods')
                {!! Form::open([
                        'route' => ['vanilo.payment-method.destroy', $paymentMethod],
                        'method' => 'DELETE',
                        'class' => 'float-right',
                        'data-confirmation-text' => __('Delete this payment method: ":name"?', ['name' => $paymentMethod->name])
                    ])
                !!}
                <button class="btn btn-outline-danger">
                    {{ __('Delete Payment Method') }}
                </button>
                {!! Form::close() !!}
            @endcan
        </div>
    </div>

@stop
