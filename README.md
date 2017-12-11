# Checkout Module

This is the checkout module for [Vanilo](https://vanilo.io).

[![Travis](https://img.shields.io/travis/vanilophp/checkout.svg?style=flat-square)](https://travis-ci.org/vanilophp/checkout)
[![Packagist version](https://img.shields.io/packagist/v/vanilo/checkout.svg?style=flat-square)](https://packagist.org/packages/vanilo/checkout)
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

- Cart (`CheckoutSubject`)
- Billing information (`BillingSubject`)
- Payment method (TBD)
- Shipping information (if cart needs shipping; address, contact)
- Shipping method (TBD)
- User (optional)
- State

Attributes:

- requires login?

## API Draft

```php

$checkout->cart; // CheckoutSubject (vanilo/contracts) getter/setter
$checkout->billingSubject; // BillingSubject
$checkout->shippingAddress; // Address
$checkout->user; // laravel user | null
$checkout->shippingMethod; // ?? name, fee => shipping module?
$checkout->paymentMethod; // ?? name, gw => payment module?
$checkout->state; // CheckoutState
```

### Billing Information

`\Vanilo\Contracts\BillingSubject`
