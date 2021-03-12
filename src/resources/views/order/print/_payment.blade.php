<h3>{{ __('Payment') }}</h3>

<table class="table table-striped">
    <tbody>
    @foreach($order->payments as $payment)
        <tr>
            <td>
                <div class="font-weight-bolder">{{ $payment->getMethod()->getName() }}</div>
                <small>{{ show_datetime($payment->updated_at) }}</small>
            </td>
            <td>
                <div class="font-weight-bolder">{{ $payment->getStatus()->label() }}</div>
                <small>{{ $payment->status_message }}</small>
            </td>
            <td>{{ format_price($payment->amount) }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
