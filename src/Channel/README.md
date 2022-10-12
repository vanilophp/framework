# Vanilo Channel Module

[![Tests](https://img.shields.io/github/workflow/status/vanilophp/channel/tests/master?style=flat-square)](https://github.com/vanilophp/channel/actions?query=workflow%3Atests)
[![Packagist version](https://img.shields.io/packagist/v/vanilo/channel.svg?style=flat-square)](https://packagist.org/packages/vanilo/channel)
[![Packagist downloads](https://img.shields.io/packagist/dt/vanilo/channel.svg?style=flat-square)](https://packagist.org/packages/vanilo/channel)
[![MIT Software License](https://img.shields.io/badge/license-MIT-blue.svg?style=flat-square)](LICENSE.md)

This is the standalone Channel module from the [Vanilo E-commerce framework](https://vanilo.io)

## Installation

(As Standalone Component)

1. `composer require vanilo/channel`
2. `php artisan vendor:publish --provider="Konekt\Concord\ConcordServiceProvider"`
3. Add `Vanilo\Channel\Providers\ModuleServiceProvider::class` to modules in `config/concord.php`
4. `php artisan migrate`

## Usage

See the [Vanilo Channels Documentation](https://vanilo.io/docs/master/channels) for more details.
