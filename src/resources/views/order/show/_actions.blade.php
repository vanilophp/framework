<div class="card mb-3">
    <div class="card-body">
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
                           @if ($order->status->value() == $value)
                           style="pointer-events: none; color: silver"
                           @else
                           onclick="event.preventDefault(); submitOrderUpdate('{{$value}}');"
                           @endif
                        >{{ $label }}</a>
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
