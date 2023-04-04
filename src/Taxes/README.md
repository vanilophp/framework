# Vanilo Taxes Module

[![Tests](https://img.shields.io/github/actions/workflow/status/vanilophp/taxes/tests.yml?branch=master&style=flat-square)](https://github.com/vanilophp/taxes/actions?query=workflow%3Atests)
[![Packagist version](https://img.shields.io/packagist/v/vanilo/taxes.svg?style=flat-square)](https://packagist.org/packages/vanilo/taxes)
[![Packagist downloads](https://img.shields.io/packagist/dt/vanilo/taxes.svg?style=flat-square)](https://packagist.org/packages/vanilo/taxes)
[![MIT Software License](https://img.shields.io/badge/license-MIT-blue.svg?style=flat-square)](LICENSE.md)

This is the standalone Taxes module from the [Vanilo E-commerce framework](https://vanilo.io)

## Installation

(As Standalone Component)

1. `composer require vanilo/taxes`
2. `php artisan vendor:publish --provider="Konekt\Concord\ConcordServiceProvider"`
3. Add `Vanilo\Taxes\Providers\ModuleServiceProvider::class` to modules in `config/concord.php`
4. `php artisan migrate`

## Usage

See the [Vanilo Taxes Documentation](https://vanilo.io/docs/master/taxes) for more details.
