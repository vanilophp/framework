@extends('appshell::layouts.default')

@section('title')
    {{ __('Viewing order :no', ['no' => $order->number]) }}
@stop

@section('content')

    <div class="row">
        <div class="col-sm-6 col-md-4">
            @component('appshell::widgets.card_with_icon', [
                    'icon' => 'account-circle',
                    'type' => 'info'
            ])
                {{ $order->billpayer->getName() }}
                @if ($order->status->is_cancelled)
                    <small>
                        <span class="badge badge-secondary">
                            {{ __('cancelled') }}
                        </span>
                    </small>
                @endif
                @slot('subtitle')
                    {{ $order->number }}
                @endslot
            @endcomponent
        </div>

        <div class="col-sm-6 col-md-4">
            @component('appshell::widgets.card_with_icon', [
                    'icon' => $order->status->is_completed ? 'check-circle' : 'dot-circle-alt',
                    'type' => $order->status->is_completed ? 'success' : 'warning'
            ])
                {{ $order->status->label() }}

                @slot('subtitle')
                    {{ __('Updated') }}
                    {{ $order->updated_at->diffForHumans() }}
                    |
                    {{ __('Created at') }}
                    {{ $order->created_at->format(__('Y-m-d H:i')) }}
                @endslot
            @endcomponent
        </div>

        <div class="col-sm-6 col-md-4">
            @component('appshell::widgets.card_with_icon', ['icon' => 'mall'])
                {{ format_price($order->total()) }}
                @slot('subtitle')
                    {{ __(':no lines on order', ['no' => $order->items->count()]) }}
                @endslot
            @endcomponent
        </div>

    </div>

    <div class="card card-accent-secondary">
        <div class="card-header">
            {{ __('Ordered Items') }}
        </div>

        <div class="card-block">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th style="width: 7%">#</th>
                        <th>{{ __('Name') }}</th>
                        <th>{{ __('Qty') }}</th>
                        <th>{{ __('Price') }}</th>
                        <th>{{ __('Subtotal') }}</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($order->getItems() as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>{{ format_price($item->price) }}</td>
                            <td>{{ format_price($item->total) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="4">
                            <div class="text-right">{{ __('Order total') }}:</div>
                        </th>
                        <th>{{ format_price($order->total()) }}</th>
                    </tr>
                </tfoot>
            </table>

        </div>
    </div>

    <div class="card">
        <div class="card-block">
            @can('edit orders')
                <div class="dropdown float-left">
                    <a class="btn btn-outline-info dropdown-toggle" href="#" data-toggle="dropdown"
                       aria-haspopup="true" aria-expanded="false"
                       id="account-dropdown-link">
                        {{ __('Update status') }}
                    </a>
                    <div class="dropdown-menu">
                        @foreach(enum('order_status')->choices() as $value => $label)
                            <a class="dropdown-item" href="#"
                               onclick="event.preventDefault(); submitOrderUpdate('{{$value}}');">
                                {{ $label }}</a>
                        @endforeach

                        {!! Form::model($order, [
                            'route'  => ['vanilo.order.update', $order],
                            'method' => 'PUT',
                            'id'     => 'order-update-form',
                            'style'  => 'display: none;'
                        ]) !!}
                            {{ Form::hidden('status', $order->status->value(), ['method' => 'PUT', 'id' => 'order_status_field']) }}

                        {!! Form::close() !!}

                    </div>

                </div>
                <script>
                    function submitOrderUpdate(status) {
                        document.getElementById('order_status_field').setAttribute('value', status);
                        document.getElementById('order-update-form').submit();
                    }
                </script>
            @endcan

            @can('delete orders')
                {!! Form::open(['route' => ['vanilo.order.destroy', $order], 'method' => 'DELETE', 'class' => "float-right"]) !!}
                <button class="btn btn-outline-danger">
                    {{ __('Delete order') }}
                </button>
                {!! Form::close() !!}
            @endcan

        </div>
    </div>



@stop
