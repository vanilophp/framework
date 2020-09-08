<div class="card mb-3">
    <div class="card-header">
        {{ __('Ordered Items') }}
    </div>

    <div class="card-body">
        <table class="table table-striped">
            <thead>
            <tr>
                <th style="width: 7%">#</th>
                <th>{{ __('Name') }}</th>
                <th>{{ __('Qty') }}</th>
                <th>{{ __('Price') }}</th>
                <th>{{ __('Subtotal') }}</th>
            </tr>
            </thead>

            <tbody>
            @foreach($order->getItems() as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ format_price($item->price) }}</td>
                    <td>{{ format_price($item->total) }}</td>
                </tr>
            @endforeach
            </tbody>
            <tfoot>
            <tr>
                <th colspan="4">
                    <div class="text-right">{{ __('Order total') }}:</div>
                </th>
                <th>{{ format_price($order->total()) }}</th>
            </tr>
            </tfoot>
        </table>

    </div>
</div>
