# Installation

### With Composer

```bash
# Create a new project (optional):
composer create-project --prefer-dist laravel/laravel vaniloapp
cd vaniloapp

# Tell composer unstable version is OK:
composer config minimum-stability dev
composer config prefer-stable true

# Install the vanilo package:
composer require vanilo/framework

# Publish module loader config:
php artisan vendor:publish --provider="Konekt\Concord\ConcordServiceProvider" --tag=config
```

Edit `config/concord.php` to have this content:

```php
<?php

return [
    'modules' => [
        Konekt\AppShell\Providers\ModuleServiceProvider::class => [
            'ui' => [
                'name' => 'Vanilo',
                'url' => '/admin/product'
            ]
        ],
        Vanilo\Framework\Providers\ModuleServiceProvider::class
    ]
];
```

The following [Concord](https://artkonekt.github.com/concord) modules should be installed now:

```
+-----+------------------------+--------+---------+------------------+------------------+
| #   | Name                   | Kind   | Version | Id               | Namespace        |
+-----+------------------------+--------+---------+------------------+------------------+
| 1.  | Konekt Address Module  | Module | 0.9.6   | konekt.address   | Konekt\Address   |
| 2.  | Konekt Customer Module | Module | 0.9.3   | konekt.customer  | Konekt\Customer  |
| 3.  | Konekt User Module     | Module | 0.1.0   | konekt.user      | Konekt\User      |
| 4.  | Konekt Acl Module      | Module | 0.1.0   | konekt.acl       | Konekt\Acl       |
| 5.  | Konekt AppShell Box    | Box    | 0.9.1   | konekt.app_shell | Konekt\AppShell  |
| 6.  | Vanilo Address Module  | Module | 0.1.0   | vanilo.address   | Vanilo\Address   |
| 7.  | Vanilo Product Module  | Module | 0.1.0   | vanilo.product   | Vanilo\Product   |
| 8.  | Vanilo Cart Module     | Module | 0.1.0   | vanilo.cart      | Vanilo\Cart      |
| 9.  | Vanilo Checkout Module | Module | 0.1.0   | vanilo.checkout  | Vanilo\Checkout  |
| 10. | Vanilo Order Module    | Module | 0.1.0   | vanilo.order     | Vanilo\Order     |
| 11. | Vanilo Framework       | Box    | 0.1.0   | vanilo.framework | Vanilo\Framework |
+-----+------------------------+--------+---------+------------------+------------------+
```
