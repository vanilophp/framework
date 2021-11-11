# Vanilo Order Module

[![Tests](https://img.shields.io/github/workflow/status/vanilophp/order/tests/master?style=flat-square)](https://github.com/vanilophp/order/actions?query=workflow%3Atests)
[![Packagist version](https://img.shields.io/packagist/v/vanilo/order.svg?style=flat-square)](https://packagist.org/packages/vanilo/order)
[![Packagist downloads](https://img.shields.io/packagist/dt/vanilo/order.svg?style=flat-square)](https://packagist.org/packages/vanilo/order)
[![MIT Software License](https://img.shields.io/badge/license-MIT-blue.svg?style=flat-square)](LICENSE.md)

This is the standalone Order module from the [Vanilo E-commerce framework](https://vanilo.io)

## Installation

(As Standalone Component)

1. `composer require vanilo/order`
2. Create the file `config/concord.php` with the following content:
    ```php
    <?php
    
    return [
        'modules' => [
            \Konekt\Address\Providers\ModuleServiceProvider::class,
            \Konekt\User\Providers\ModuleServiceProvider::class,
            \Vanilo\Order\Providers\ModuleServiceProvider::class,
        ]
    ];
    ```
4. `php artisan migrate`

## Usage

See the [Vanilo Order Documentation](https://vanilo.io/docs/master/order) for more details.
