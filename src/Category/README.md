# Vanilo Category Module

[![Tests](https://img.shields.io/github/actions/workflow/status/vanilophp/category/tests.yml?branch=master&style=flat-square)](https://github.com/vanilophp/category/actions?query=workflow%3Atests)
[![Packagist version](https://img.shields.io/packagist/v/vanilo/category.svg?style=flat-square)](https://packagist.org/packages/vanilo/category)
[![Packagist downloads](https://img.shields.io/packagist/dt/vanilo/category.svg?style=flat-square)](https://packagist.org/packages/vanilo/category)
[![MIT Software License](https://img.shields.io/badge/license-MIT-blue.svg?style=flat-square)](LICENSE.md)

This is the standalone Category module from the [Vanilo E-commerce framework](https://vanilo.io)

## Installation

(As Standalone Component)

1. `composer require vanilo/category`
2. `php artisan vendor:publish --provider="Konekt\Concord\ConcordServiceProvider"`
3. Add `Vanilo\Category\Providers\ModuleServiceProvider::class` to modules in `config/concord.php`
4. `php artisan migrate`

## Usage

See the [Vanilo Categorization Documentation](https://vanilo.io/docs/master/categorization) for more details.
