@extends('appshell::layouts.default')

@section('title')
    {{ __('Orders') }}
@stop

@section('content')

    <div class="card card-accent-secondary">

        <div class="card-header">
            @yield('title')

            <div class="card-actionbar">
            </div>

        </div>

        <div class="card-block">
            <table class="table table-striped table-hover">
                <thead>
                <tr>
                    <th>{{ __('Number') }}</th>
                    <th>{{ __('Name') }}</th>
                    <th>{{ __('Date') }}</th>
                    <th>{{ __('Status') }}</th>
                    <th style="width: 10%">&nbsp;</th>
                </tr>
                </thead>

                <tbody>
                @foreach($orders as $order)
                    <tr>
                        <td>
                            @can('view orders')
                                <a href="{{ route('vanilo.order.show', $order) }}">{{ $order->number }}</a>
                            @else
                                {{ $order->number }}
                            @endcan
                        </td>
                        <td>{{ $order->billpayer->getName() }}</td>
                        <td><span title="{{ $order->created_at }}">{{ $order->created_at->diffForHumans() }}</span></td>
                        <td>{{ $order->status->label() }}</td>
                        <td>
                            @can('edit orders')
                                <a href="{{ route('vanilo.order.edit', $order) }}"
                                   class="btn btn-xs btn-outline-primary btn-show-on-tr-hover float-right">{{ __('Edit') }}</a>
                            @endcan

                            @can('delete orders')
                                <a href="{{ route('vanilo.order.destroy', $order) }}"
                                   class="btn btn-xs btn-outline-danger btn-show-on-tr-hover float-right">{{ __('Delete') }}</a>
                            @endcan
                        </td>
                    </tr>
                @endforeach
                </tbody>

            </table>

        </div>
    </div>

@stop
