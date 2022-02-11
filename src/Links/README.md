# Vanilo Links Module

[![Tests](https://img.shields.io/github/workflow/status/vanilophp/links/tests/master?style=flat-square)](https://github.com/vanilophp/links/actions?query=workflow%3Atests)
[![Packagist version](https://img.shields.io/packagist/v/vanilo/links.svg?style=flat-square)](https://packagist.org/packages/vanilo/links)
[![Packagist downloads](https://img.shields.io/packagist/dt/vanilo/links.svg?style=flat-square)](https://packagist.org/packages/vanilo/links)
[![MIT Software License](https://img.shields.io/badge/license-MIT-blue.svg?style=flat-square)](LICENSE.md)

This is the standalone Links module from the [Vanilo E-commerce framework](https://vanilo.io)

## Installation

(As Standalone Component)

1. `composer require vanilo/links`
2. `php artisan vendor:publish --provider=Konekt\Concord\ConcordServiceProvider`
3. Add `Vanilo\Links\Providers\ModuleServiceProvider::class` to modules in `config/concord.php`
4. `php artisan migrate`

## Usage

See the [Vanilo Links Documentation](https://vanilo.io/docs/master/links) for more details.
