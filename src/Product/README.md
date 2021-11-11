# Vanilo Product Module

[![Tests](https://img.shields.io/github/workflow/status/vanilophp/product/tests/master?style=flat-square)](https://github.com/vanilophp/product/actions?query=workflow%3Atests)
[![Packagist version](https://img.shields.io/packagist/v/vanilo/product.svg?style=flat-square)](https://packagist.org/packages/vanilo/product)
[![Packagist downloads](https://img.shields.io/packagist/dt/vanilo/product.svg?style=flat-square)](https://packagist.org/packages/vanilo/product)
[![MIT Software License](https://img.shields.io/badge/license-MIT-blue.svg?style=flat-square)](LICENSE.md)

This is the standalone Product module from the [Vanilo E-commerce framework](https://vanilo.io)

## Installation

(As Standalone Component)

1. `composer require vanilo/product`
2. `php artisan vendor:publish --provider=Konekt\Concord\ConcordServiceProvider`
3. Add `Vanilo\Product\Providers\ModuleServiceProvider::class` to modules in `config/concord.php`
4. `php artisan migrate`

## Usage

See the [Vanilo Product Documentation](https://vanilo.io/docs/master/products) for more details.
