<div class="card mb-3">
    <div class="card-header">
        {{ __('Bill To') }}
    </div>

    <?php
    $billpayer = $order->billpayer;
    $billingAddress = $billpayer->getBillingAddress();
    ?>
    <div class="card-body">
        <h6>{{ $billpayer->getName() }}</h6>
        @if( $billpayer->isOrganization())
            {{ $billpayer->getTaxNumber() }}<br>
            {{ $billpayer->registration_nr }}
        @endif
        <p>
            {{ $billpayer->email }}@if($billpayer->phone), {{ $billpayer->phone }} @endif<br>
            {{ $billingAddress->getAddress() }}<br>
            {{ $billingAddress->getCity() }}@if($billingAddress->getPostalCode()), {{ $billingAddress->getPostalCode() }} @endif<br>
            {{ $billingAddress->country->name }}<br>
        </p>
    </div>
</div>

<div class="card mb-3">
    <div class="card-header">
        {{ __('Ship To') }}
    </div>

    <?php $shippingAddress = $order->getShippingAddress(); ?>
    <div class="card-body">
        <h6>{{ $shippingAddress->getName() }}</h6>
        <p>
            {{ $shippingAddress->getAddress() }}<br>
            {{ $shippingAddress->getCity() }}@if($shippingAddress->getPostalCode()), {{ $shippingAddress->getPostalCode() }} @endif<br>
            {{ $shippingAddress->country->name }}<br>
        </p>
    </div>
</div>
