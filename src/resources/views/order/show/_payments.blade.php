<div class="card mb-3">
    <div class="card-header">
        {{ __('Payments') }}
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
                    <td>{{ format_price($payment->amount) }}</td>
                    <td>{{ $payment->getStatus()->label() }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
