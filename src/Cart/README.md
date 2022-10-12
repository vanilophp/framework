# Vanilo Cart Module

[![Tests](https://img.shields.io/github/workflow/status/vanilophp/cart/tests/master?style=flat-square)](https://github.com/vanilophp/cart/actions?query=workflow%3Atests)
[![Packagist version](https://img.shields.io/packagist/v/vanilo/cart.svg?style=flat-square)](https://packagist.org/packages/vanilo/cart)
[![Packagist downloads](https://img.shields.io/packagist/dt/vanilo/cart.svg?style=flat-square)](https://packagist.org/packages/vanilo/cart)
[![MIT Software License](https://img.shields.io/badge/license-MIT-blue.svg?style=flat-square)](LICENSE.md)

This is the standalone cart module of the [Vanilo E-commerce Framework](https://vanilo.io).

## Installation

(As Standalone Component)

1. `composer require vanilo/cart`
2. `php artisan vendor:publish --provider="Konekt\Concord\ConcordServiceProvider"`
3. Add `Vanilo\Cart\Providers\ModuleServiceProvider::class` to modules in `config/concord.php`
4. `php artisan migrate`

## Usage

See the [Vanilo Cart Documentation](https://vanilo.io/docs/master/cart) for more details. 
