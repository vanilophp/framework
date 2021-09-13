@extends('appshell::layouts.private')

@section('title')
    {{ __('Payment Methods') }}
@stop

@section('content')

    <div class="card card-accent-secondary">

        <div class="card-header">
            @yield('title')

            <div class="card-actionbar">
                @can('create payment methods')
                    <a href="{{ route('vanilo.payment-method.create') }}" class="btn btn-sm btn-outline-success float-right">
                        {!! icon('+') !!}
                        {{ __('New Payment Method') }}
                    </a>
                @endcan
            </div>

        </div>

        <div class="card-body">
            <table class="table table-striped table-hover">
                <thead>
                <tr>
                    <th>{{ __('Name') }}</th>
                    <th>{{ __('Gateway') }}</th>
                    <th>{{ __('No. of Transactions') }}</th>
                    <th>{{ __('Enabled') }}</th>
                    <th style="width: 10%">&nbsp;</th>
                </tr>
                </thead>

                <tbody>
                @foreach($paymentMethods as $paymentMethod)
                    <tr>
                        <td>
                            <span class="font-lg mb-3 font-weight-bold">
                            @can('view payment methods')
                                    <a href="{{ route('vanilo.payment-method.show', $paymentMethod) }}">{{ $paymentMethod->getName() }}</a>
                                @else
                                    {{ $paymentMethod->getName() }}
                                @endcan
                            </span>
                        </td>
                        <td>{{ $paymentMethod->getGateway()->getName() }}</td>
                        <td>{{ (int) $paymentMethod->transaction_count }}</td>
                        <td>
                            @if($paymentMethod->isEnabled())
                                {!! icon('active') !!}
                            @else
                                {!! icon('inactive') !!}
                            @endif
                        </td>
                        <td>
                            @can('edit payment methods')
                                <a href="{{ route('vanilo.payment-method.edit', $paymentMethod) }}"
                                   class="btn btn-xs btn-outline-primary btn-show-on-tr-hover float-right">{{ __('Edit') }}</a>
                            @endcan

                            @can('delete payment methods')
                                {{ Form::open([
                                    'url' => route('vanilo.payment-method.destroy', $paymentMethod),
                                    'data-confirmation-text' => __('Delete this payment method: ":name"?', ['name' => $paymentMethod->getName()]),
                                    'method' => 'DELETE'
                                ])}}
                                <button class="btn btn-xs btn-outline-danger btn-show-on-tr-hover float-right">{{ __('Delete') }}</button>
                                {{ Form::close() }}
                            @endcan
                        </td>
                    </tr>
                @endforeach
                </tbody>

            </table>

        </div>
    </div>

@stop
