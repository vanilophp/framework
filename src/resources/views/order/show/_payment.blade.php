<div class="card mb-3">
    <div class="card-header">
        {{ __('Payment') }}
    </div>

    <div class="card-body">
        <table class="table table-striped">
            <tbody>
            @forelse($order->payments as $payment)
                <tr>
                    <td>
                        <span class="font-lg mb-3 font-weight-bold" title="{{ $payment->hash }}">
                            <a href="#" title="{{ __('Click to open payment history...') }}"
                               data-toggle="modal" data-target="#payment-history">
                                {{ $payment->getMethod()->getName() }}
                            </a>
                        </span>
                        <div class="text-muted">
                            {{ show_datetime($payment->updated_at) }}
                        </div>
                    </td>
                    <td>
                        <div>
                            <span class="badge badge-pill badge-primary">
                                {{ $payment->getStatus()->label() }}
                            </span>
                        </div>
                        <span class="font-italic">{{ $payment->status_message }}</span>
                    </td>
                    <td>{{ format_price($payment->amount) }}</td>
                </tr>
            @empty
                <tr>
                    <td>{{ __('There are no payments assigned to this order') }}</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

@includeWhen($order->payments->isNotEmpty(), 'vanilo::order.show._payment_history')
