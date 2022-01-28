# Vanilo Shipment Module

[![Tests](https://img.shields.io/github/workflow/status/vanilophp/shipment/tests/master?style=flat-square)](https://github.com/vanilophp/shipment/actions?query=workflow%3Atests)
[![Packagist version](https://img.shields.io/packagist/v/vanilo/shipment.svg?style=flat-square)](https://packagist.org/packages/vanilo/shipment)
[![Packagist downloads](https://img.shields.io/packagist/dt/vanilo/shipment.svg?style=flat-square)](https://packagist.org/packages/vanilo/shipment)
[![MIT Software License](https://img.shields.io/badge/license-MIT-blue.svg?style=flat-square)](LICENSE.md)

This is the standalone Shipment module from the [Vanilo E-commerce framework](https://vanilo.io)

## Installation

(As Standalone Component)

1. `composer require vanilo/shipment`
2. `php artisan vendor:publish --provider=Konekt\Concord\ConcordServiceProvider`
3. Add `Vanilo\Shipment\Providers\ModuleServiceProvider::class` to modules in `config/concord.php`
4. `php artisan migrate`

## Usage

See the [Vanilo Shipment Documentation](https://vanilo.io/docs/master/shipping) for more details.
