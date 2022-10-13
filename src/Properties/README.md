# Vanilo Properties Module

[![Tests](https://img.shields.io/github/workflow/status/vanilophp/properties/tests/master?style=flat-square)](https://github.com/vanilophp/properties/actions?query=workflow%3Atests)
[![Packagist version](https://img.shields.io/packagist/v/vanilo/properties.svg?style=flat-square)](https://packagist.org/packages/vanilo/properties)
[![Packagist downloads](https://img.shields.io/packagist/dt/vanilo/properties.svg?style=flat-square)](https://packagist.org/packages/vanilo/properties)
[![MIT Software License](https://img.shields.io/badge/license-MIT-blue.svg?style=flat-square)](LICENSE.md)

This is the standalone Properties module from the [Vanilo E-commerce framework](https://vanilo.io)

## Installation

(As Standalone Component)

1. `composer require vanilo/properties`
2. `php artisan vendor:publish --provider="Konekt\Concord\ConcordServiceProvider"`
3. Add `Vanilo\Properties\Providers\ModuleServiceProvider::class` to modules in `config/concord.php`
4. `php artisan migrate`

## Usage

See the [Vanilo Properties Documentation](https://vanilo.io/docs/master/properties) for more details.
