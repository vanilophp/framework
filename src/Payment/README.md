# Vanilo Payment Module

[![Tests](https://img.shields.io/github/actions/workflow/status/vanilophp/payment/tests.yml?branch=master&style=flat-square)](https://github.com/vanilophp/payment/actions?query=workflow%3Atests)
[![Packagist version](https://img.shields.io/packagist/v/vanilo/payment.svg?style=flat-square)](https://packagist.org/packages/vanilo/payment)
[![Packagist downloads](https://img.shields.io/packagist/dt/vanilo/payment.svg?style=flat-square)](https://packagist.org/packages/vanilo/payment)
[![MIT Software License](https://img.shields.io/badge/license-MIT-blue.svg?style=flat-square)](LICENSE.md)

This is the standalone Payment module from the [Vanilo E-commerce framework](https://vanilo.io)

## Installation

(As Standalone Component)

1. `composer require vanilo/checkout`
2. `php artisan vendor:publish --provider="Konekt\Concord\ConcordServiceProvider"`
3. Add `Vanilo\Payment\Providers\ModuleServiceProvider::class` to modules in `config/concord.php`
4. `php artisan migrate`

## Usage

See the [Vanilo Payment Documentation](https://vanilo.io/docs/master/payments) for more details.
