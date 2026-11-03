# Vanilo Snapshot Module

[![Tests](https://img.shields.io/github/actions/workflow/status/vanilophp/snapshot/tests.yml?branch=master&style=flat-square)](https://github.com/vanilophp/snapshot/actions?query=workflow%3Atests)
[![Packagist version](https://img.shields.io/packagist/v/vanilo/snapshot.svg?style=flat-square)](https://packagist.org/packages/vanilo/snapshot)
[![Packagist downloads](https://img.shields.io/packagist/dt/vanilo/snapshot.svg?style=flat-square)](https://packagist.org/packages/vanilo/snapshot)
[![MIT Software License](https://img.shields.io/badge/license-MIT-blue.svg?style=flat-square)](LICENSE.md)

This is the standalone Snapshot from the [Vanilo E-commerce framework](https://vanilo.io)

## Installation

(As Standalone Component)

1. `composer require vanilo/snapshot`
2. `php artisan vendor:publish --provider="Konekt\Concord\ConcordServiceProvider"`
3. Add `Vanilo\Snapshot\Providers\ModuleServiceProvider::class` to modules in `config/concord.php`
4. `php artisan migrate`

## Usage

See the [Vanilo Snapshot Documentation](https://vanilo.io/docs/master/snapshot) for more details.
