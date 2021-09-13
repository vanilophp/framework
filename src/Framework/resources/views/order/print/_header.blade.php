<?php
    $billpayer = $order->billpayer;
    $billingAddress = $billpayer->getBillingAddress();
    $shippingAddress = $order->getShippingAddress();
?>
<table class="table">
    <thead>
        <tr>
            <th>{{ __('Bill To') }}</th>
            <th>{{ __('Ship To') }}</th>
            <th>{{ __('Order Details') }}</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>
                <h3>{{ $order->billpayer->getName() }}</h3>
                @if( $billpayer->isOrganization())
                    {{ $billpayer->getTaxNumber() }}<br>
                    {{ $billpayer->registration_nr }}
                @endif
                {{ $billpayer->email }}@if($billpayer->phone), {{ $billpayer->phone }} @endif<br>
                {{ $billingAddress->getAddress() }}<br>
                {{ $billingAddress->getCity() }}@if($billingAddress->getPostalCode()), {{ $billingAddress->getPostalCode() }} @endif<br>
                {{ $billingAddress->country->name }}<br>
            </td>
            <td>
                @isset($shippingAddress)
                    <h3>{{ $shippingAddress->getName() }}</h3>
                    {{ $shippingAddress->getAddress() }}<br>
                    {{ $shippingAddress->getCity() }}@if($shippingAddress->getPostalCode()), {{ $shippingAddress->getPostalCode() }} @endif<br>
                    {{ $shippingAddress->country->name }}<br>
                @else
                    {{ __('No shipping information') }}
                @endisset
            </td>
            <td>
                <h3>{{ __('Status: :status', ['status' => $order->status->label()]) }}</h3>
                {{ __('Order date') }} {{ show_datetime($order->created_at) }}<br>
                {{ format_price($order->total()) }}<br>
            </td>
        </tr>
    </tbody>
</table>
