# Vanilo Master Product Module

[![Tests](https://img.shields.io/github/workflow/status/vanilophp/master-product/tests/master?style=flat-square)](https://github.com/vanilophp/master-product/actions?query=workflow%3Atests)
[![Packagist version](https://img.shields.io/packagist/v/vanilo/master-product.svg?style=flat-square)](https://packagist.org/packages/vanilo/master-product)
[![Packagist downloads](https://img.shields.io/packagist/dt/vanilo/master-product.svg?style=flat-square)](https://packagist.org/packages/vanilo/master-product)
[![MIT Software License](https://img.shields.io/badge/license-MIT-blue.svg?style=flat-square)](LICENSE.md)

This is the standalone Master Product module of the [Vanilo E-commerce Framework](https://vanilo.io).

## Installation

(As Standalone Component)

1. `composer require vanilo/master-product`
2. `php artisan vendor:publish --provider="Konekt\Concord\ConcordServiceProvider"`
3. Add `Vanilo\MasterProduct\Providers\ModuleServiceProvider::class` to modules in `config/concord.php`
4. `php artisan migrate`

## Usage

See the [Vanilo Master Products Documentation](https://vanilo.io/docs/master/master-products) for more details.
