# Vanilo Video Module

[![Tests](https://img.shields.io/github/actions/workflow/status/vanilophp/video/tests.yml?branch=master&style=flat-square)](https://github.com/vanilophp/video/actions?query=workflow%3Atests)
[![Packagist version](https://img.shields.io/packagist/v/vanilo/video.svg?style=flat-square)](https://packagist.org/packages/vanilo/video)
[![Packagist downloads](https://img.shields.io/packagist/dt/vanilo/video.svg?style=flat-square)](https://packagist.org/packages/vanilo/video)
[![MIT Software License](https://img.shields.io/badge/license-MIT-blue.svg?style=flat-square)](LICENSE.md)

This is the standalone Video module from the [Vanilo E-commerce framework](https://vanilo.io)

## Installation

(As Standalone Component)

1. `composer require vanilo/video`
2. `php artisan vendor:publish --provider="Konekt\Concord\ConcordServiceProvider"`
3. Add `Vanilo\Video\Providers\ModuleServiceProvider::class` to modules in `config/concord.php`
4. `php artisan migrate`

## Usage

See the [Vanilo Video Documentation](https://vanilo.io/docs/master/video) for more details.
