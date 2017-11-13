# Checkout Module

This is the checkout module for [Vanilo](https://vanilo.io).

[![Travis](https://img.shields.io/travis/vanilophp/checkout.svg?style=flat-square)](https://travis-ci.org/vanilophp/checkout)
[![Packagist version](https://img.shields.io/packagist/vpre/vanilo/checkout.svg?style=flat-square)](https://packagist.org/packages/vanilo/checkout)
[![Packagist downloads](https://img.shields.io/packagist/dt/vanilo/checkout.svg?style=flat-square)](https://packagist.org/packages/vanilo/checkout)
[![StyleCI](https://styleci.io/repos/109258256/shield?branch=master)](https://styleci.io/repos/109258256)
[![MIT Software License](https://img.shields.io/badge/license-MIT-blue.svg?style=flat-square)](LICENSE.md)

## Installation

```bash
composer require vanilo/checkout
php artisan migrate
```

## Anatomy Of A Checkout

It has:

- Cart (Checkout Subject)
- Billing information (client, address, contact)
- Payment method
- Shipping information (if cart needs shipping; address, contact)
- Shipping method
- User (optional)
- State

Attributes:

- requires login?

## API Draft

```php

$checkout->cart; // CheckoutSubject (vanilo/contracts) getter/setter
$checkout->billingAddress; //konekt/address ?? billing/address
$checkout->shippingAddress; //konekt/address ?? shipping/address
$checkout->user; // laravel user | null
$checkout->shippingMethod; // ?? name, fee => shipping module?
$checkout->paymentMethod; // ?? name, gw => payment module?
$checkout->state; // CheckoutState
```

### Billing Information

1. Client: Org|Person
2. (Postal) Address
3. Contact Data (Phone, name, E-mail) -> Person ... ie. 1 if 1 is not org... aaargh!
