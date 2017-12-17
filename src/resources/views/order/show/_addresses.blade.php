<div class="row">
    <div class="col-sm-6">
        <div class="card">
            <div class="card-header">
                {{ __('Bill To') }}
            </div>

            <?php
                $billpayer = $order->billpayer;
                $billingAddress = $billpayer->getBillingAddress();
            ?>
            <div class="card-block">
                <h6>{{ $billpayer->getName() }}</h6>
                @if( $billpayer->isOrganization())
                    {{ $billpayer->getTaxNumber() }}
                @endif
                <p>
                    {{ $billingAddress->getAddress() }}<br>
                    {{ $billingAddress->getCity() }}@if($billingAddress->getPostalCode()), {{ $billingAddress->getPostalCode() }} @endif<br>
                    {{ $billingAddress->country->name }}<br>
                </p>
            </div>
        </div>
    </div>

    <div class="col-sm-6">
        <div class="card">
            <div class="card-header">
                {{ __('Ship To') }}
            </div>

            <?php $shippingAddress = $order->getShippingAddress(); ?>
            <div class="card-block">
                <h6>{{ $shippingAddress->getName() }}</h6>
                <p>
                    {{ $shippingAddress->getAddress() }}<br>
                    {{ $shippingAddress->getCity() }}@if($shippingAddress->getPostalCode()), {{ $shippingAddress->getPostalCode() }} @endif<br>
                    {{ $shippingAddress->country->name }}<br>
                </p>
            </div>
        </div>
    </div>

</div>
