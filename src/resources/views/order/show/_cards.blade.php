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
