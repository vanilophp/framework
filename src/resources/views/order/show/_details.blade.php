<div class="card mb-3">
    <div class="card-header">
        {{ __('Additonal Details') }}
    </div>

    <div class="card-body">
        <h6>{{ __('Notes') }}</h6>
        <div class="font-italic">
            {!! nl2br($order->notes) !!}
        </div>
    </div>
</div>
