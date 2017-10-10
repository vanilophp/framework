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
composer require konekt/vanilo

# Publish module loader config:
php artisan vendor:publish --provider="Konekt\Concord\ConcordServiceProvider" --tag=config
```

Edit `config/concord.php` to have this content:

```php
<?php

return [
    'modules' => [
        Konekt\Vanilo\Providers\ModuleServiceProvider::class,
        Konekt\AppShell\Providers\ModuleServiceProvider::class
    ]
];
```

The following [Concord](https://artkonekt.github.com/concord) modules should be installed now:

```
+----+-----------------------+--------+---------+------------------+-----------------+
| #  | Name                  | Kind   | Version | Id               | Namespace       |
+----+-----------------------+--------+---------+------------------+-----------------+
| 1. | Vanilo Core Module    | Module | 0.1.0   | konekt.vanilo    | Konekt\Vanilo   |
| 2. | Konekt Address Module | Module | 0.9.3   | konekt.address   | Konekt\Address  |
| 3. | Client Box            | Box    | 0.1.0   | konekt.client    | Konekt\Client   |
| 4. | Konekt User Module    | Module | 0.1.0   | konekt.user      | Konekt\User     |
| 5. | Konekt Acl Module     | Module | 0.1.0   | konekt.acl       | Konekt\Acl      |
| 6. | Konekt AppShell Box   | Box    | 0.1.0   | konekt.app_shell | Konekt\AppShell |
+----+-----------------------+--------+---------+------------------+-----------------+
```
