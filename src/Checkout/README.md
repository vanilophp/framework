# Checkout Module

This is the checkout module for [Vanilo](https://vanilo.io).

[![Tests](https://img.shields.io/github/workflow/status/vanilophp/checkout/tests/master?style=flat-square)](https://github.com/vanilophp/checkout/actions?query=workflow%3Atests)
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
- Custom Data (notes, etc)
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

### Custom Checkout Attributes

```php
// Set custom attribute
Checkout::setCustomAttribute('gift', 'Unisex T-Shirt L');
// Retrieve custom attribute
echo Checkout::getCustomAttribute('gift');
// "Unisex T-Shirt L"

// Retrieve all custom attributes at once
Checkout::getCustomAttributes();
// array(2) 
// 'gift'          => 'Unisex T-Shirt L'
// 'gdpr_accepted' => true
```
