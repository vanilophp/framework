# Vanilo Promotion Module

[![Tests](https://img.shields.io/github/actions/workflow/status/vanilophp/promotion/tests.yml?branch=master&style=flat-square)](https://github.com/vanilophp/promotion/actions?query=workflow%3Atests)
[![Packagist version](https://img.shields.io/packagist/v/vanilo/promotion.svg?style=flat-square)](https://packagist.org/packages/vanilo/promotion)
[![Packagist downloads](https://img.shields.io/packagist/dt/vanilo/promotion.svg?style=flat-square)](https://packagist.org/packages/vanilo/promotion)
[![MIT Software License](https://img.shields.io/badge/license-MIT-blue.svg?style=flat-square)](LICENSE.md)

This is the standalone Promotion module of the [Vanilo E-commerce Framework](https://vanilo.io).

## Installation

(As Standalone Component)

1. `composer require vanilo/promotion`
2. `php artisan vendor:publish --provider="Konekt\Concord\ConcordServiceProvider"`
3. Add `Vanilo\Promotion\Providers\ModuleServiceProvider::class` to modules in `config/concord.php`
4. `php artisan migrate`

## Usage

See the [Vanilo Promotions Documentation](https://vanilo.io/docs/4.x/promotions) for more details.
