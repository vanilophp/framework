<div class="card-deck mb-3">

    @component('appshell::widgets.card_with_icon', [
            'icon' => 'customer',
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

    @component('appshell::widgets.card_with_icon', [
            'icon' => enum_icon($order->status),
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

    @component('appshell::widgets.card_with_icon', ['icon' => 'bag'])
        {{ format_price($order->total()) }}
        @slot('subtitle')
            {{ __(':no lines on order', ['no' => $order->items->count()]) }}
        @endslot
    @endcomponent

</div>
