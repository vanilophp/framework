<div id="payment-history" class="modal fade" tabindex="-1" role="dialog"
     aria-labelledby="payment-history-title" aria-hidden="true">

    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="payment-history-title">
                    {{ __('Payment History') }}
                    <span class="badge badge-pill badge-light" title="{{ __('Payment hash') }}">
                        {{ $payment->hash }}
                    </span>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <table class="table table-sm table-striped">
                    <thead>
                        <tr>
                            <th colspan="2">{{ __('Transaction details') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th>{{ __('Message') }}</th>
                            <th>{{ __(':gateway Status', ['gateway' => ucfirst($payment->method->gateway)]) }}</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($payment->history as $entry)
                        <tr>
                            <td>
                                <span class="font-lg mb-3 font-weight-bold" title="{{ $entry->created_at }}">
                                    {{ show_datetime($entry->created_at) }}
                                </span>
                                <div class="text-muted" title="{{ __('Transaction id') }}">
                                    {!! $entry->transaction_number ?: '&nbsp;' !!}
                                </div>
                            </td>
                            <td>
                                <div class="text-muted" title="{{ __('Transaction amount') }}">
                                    {{ sprintf('%.2f %s', $entry->transaction_amount, $payment->getCurrency()) }}
                                </div>
                            </td>
                            <td>
                                <span class="badge badge-pill badge-primary">{{ $entry->new_status->label() }}</span>
                            </td>
                            <td><span class="font-italic">{{ $entry->message }}</span></td>
                            <td>
                                <span class="badge badge-pill badge-secondary">{{ $entry->native_status }}</span>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link" data-dismiss="modal">{{ __('Close') }}</button>
            </div>
        </div>
    </div>
</div>
