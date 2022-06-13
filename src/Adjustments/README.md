# Vanilo Adjustments Module

[![Tests](https://img.shields.io/github/workflow/status/vanilophp/adjustments/tests/master?style=flat-square)](https://github.com/vanilophp/adjustments/actions?query=workflow%3Atests)
[![Packagist version](https://img.shields.io/packagist/v/vanilo/adjustments.svg?style=flat-square)](https://packagist.org/packages/vanilo/adjustments)
[![Packagist downloads](https://img.shields.io/packagist/dt/vanilo/adjustments.svg?style=flat-square)](https://packagist.org/packages/vanilo/adjustments)
[![MIT Software License](https://img.shields.io/badge/license-MIT-blue.svg?style=flat-square)](LICENSE.md)

This is the standalone adjustments module of the [Vanilo E-commerce Framework](https://vanilo.io).

## Installation

(As Standalone Component)

1. `composer require vanilo/adjustments`
2. `php artisan vendor:publish --provider=Konekt\Concord\ConcordServiceProvider`
3. Add `Vanilo\Adjustments\Providers\ModuleServiceProvider::class` to modules in `config/concord.php`
4. `php artisan migrate`

## Usage

See the [Vanilo Adjustments Documentation](https://vanilo.io/docs/master/adjustments) for more details.
