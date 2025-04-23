# Vanilo Translation Module

[![Tests](https://img.shields.io/github/actions/workflow/status/vanilophp/translation/tests.yml?branch=master&style=flat-square)](https://github.com/vanilophp/translation/actions?query=workflow%3Atests)
[![Packagist version](https://img.shields.io/packagist/v/vanilo/translation.svg?style=flat-square)](https://packagist.org/packages/vanilo/translation)
[![Packagist downloads](https://img.shields.io/packagist/dt/vanilo/translation.svg?style=flat-square)](https://packagist.org/packages/vanilo/translation)
[![MIT Software License](https://img.shields.io/badge/license-MIT-blue.svg?style=flat-square)](LICENSE.md)

This is the standalone Translation module from the [Vanilo E-commerce framework](https://vanilo.io)

## Installation

(As Standalone Component)

1. `composer require vanilo/translation`
2. `php artisan vendor:publish --provider="Konekt\Concord\ConcordServiceProvider"`
3. Add `Vanilo\Translation\Providers\ModuleServiceProvider::class` to modules in `config/concord.php`
4. `php artisan migrate`

## Usage

See the [Vanilo Translation Documentation](https://vanilo.io/docs/master/translation) for more details.
