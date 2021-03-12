<div class="card mb-3">
    <div class="card-header">
        {{ __('Payment') }}
    </div>

    <div class="card-body">
        <table class="table table-striped">
            <tbody>
            @foreach($order->payments as $payment)
                <tr>
                    <td>
                        <span class="font-lg mb-3 font-weight-bold" title="{{ $payment->hash }}">
                            {{ $payment->getMethod()->getName() }}
                        </span>
                        <div class="text-muted">
                            {{ show_datetime($payment->updated_at) }}
                        </div>
                    </td>
                    <td>
                        <div class="font-weight-bolder">{{ $payment->getStatus()->label() }}</div>
                        <span class="font-italic">{{ $payment->status_message }}</span>
                    </td>
                    <td>{{ format_price($payment->amount) }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
