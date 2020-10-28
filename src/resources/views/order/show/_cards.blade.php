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
        {{ show_datetime($order->updated_at) }}
        |
        {{ __('Created at') }}
        {{ show_datetime($order->created_at) }}
    @endslot
@endcomponent

@component('appshell::widgets.card_with_icon', ['icon' => 'bag'])
    {{ format_price($order->total()) }}
    @slot('subtitle')
        {{ __(':no lines on order', ['no' => $order->items->count()]) }}
    @endslot
@endcomponent
