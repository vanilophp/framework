# Vanilo Checkout Module

[![Tests](https://img.shields.io/github/workflow/status/vanilophp/checkout/tests/master?style=flat-square)](https://github.com/vanilophp/checkout/actions?query=workflow%3Atests)
[![Packagist version](https://img.shields.io/packagist/v/vanilo/checkout.svg?style=flat-square)](https://packagist.org/packages/vanilo/checkout)
[![Packagist downloads](https://img.shields.io/packagist/dt/vanilo/checkout.svg?style=flat-square)](https://packagist.org/packages/vanilo/checkout)
[![MIT Software License](https://img.shields.io/badge/license-MIT-blue.svg?style=flat-square)](LICENSE.md)

This is the standalone Checkout module from the [Vanilo E-commerce framework](https://vanilo.io)

## Installation

(As Standalone Component)

1. `composer require vanilo/checkout`
2. `php artisan vendor:publish --provider=Konekt\Concord\ConcordServiceProvider`
3. Add `Vanilo\Checkout\Providers\ModuleServiceProvider::class` to modules in `config/concord.php`
4. `php artisan migrate`

## Usage

See the [Vanilo Checkout Documentation](https://vanilo.io/docs/master/checkout) for more details.
