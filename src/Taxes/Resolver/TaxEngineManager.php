<?php

declare(strict_types=1);

/**
 * Contains the TaxEngineManager class.
 *
 * @copyright   Copyright (c) 2024 Vanilo UG
 * @author      Attila Fulop
 * @license     MIT
 * @since       2024-02-08
 *
 */

namespace Vanilo\Taxes\Resolver;

use Illuminate\Contracts\Foundation\Application;
use InvalidArgumentException;
use LogicException;
use Vanilo\Contracts\Address;
use Vanilo\Taxes\Contracts\Taxable;
use Vanilo\Taxes\Contracts\TaxRate;
use Vanilo\Taxes\Contracts\TaxRateResolver;
use Vanilo\Taxes\Exceptions\InvalidTaxConfigurationException;

class TaxEngineManager
{
    public const NULL_DRIVER = 'none';

    /** The array of registered Tax Resolver drivers */
    protected static array $drivers = [
        self::NULL_DRIVER => NullTaxRateResolver::class
    ];

    /** The array of resolved tax resolver instances.*/
    protected array $instances = [];

    public function __construct(
        protected Application $app,
    ) {
    }

    public function __call(string $method, array $arguments)
    {
        return $this->driver()->$method(...$arguments);
    }

    public function driver(?string $name = null): TaxRateResolver
    {
        $name = $name ?: $this->getDefaultDriver();

        return $this->instances[$name] ??= $this->resolve($name);
    }

    public function extend(string $name, string|callable $driver): void
    {
        if (is_callable($driver) || (is_string($driver) && is_subclass_of($driver, TaxRateResolver::class))) {
            self::$drivers[$name] = $driver;
            if (isset($this->instances[$name])) {
                unset($this->instances[$name]);
            }

            return;
        }

        throw new InvalidArgumentException("Can not register the [{$name}] Tax engine driver, since the passed driver is neither callable, nor a `TaxRateResolver` class");
    }

    public function findTaxRate(Taxable $taxable, ?Address $billingAddress = null, ?Address $shippingAddress = null): ?TaxRate
    {
        return $this->driver()->findTaxRate($taxable, $billingAddress, $shippingAddress);
    }

    protected function getDefaultDriver(): string
    {
        return $this->app['config']['vanilo.taxes.engine.driver'] ?? self::NULL_DRIVER;
    }

    protected function resolve(string $name): TaxRateResolver
    {
        if (null === $driver = self::$drivers[$name]) {
            throw new InvalidTaxConfigurationException("The tax engine driver [{$name}] is not defined.");
        } elseif (self::NULL_DRIVER === $driver) {
            return new NullTaxRateResolver();
        }

        $instance = match (true) {
            is_string($driver) && class_exists($driver) => $this->app->make($driver),
            is_callable($driver) => call_user_func($driver, $this->app['config']["vanilo.taxes.engine.{$name}"] ?? []),
        };

        if ($instance instanceof TaxRateResolver) {
            return $instance;
        }

        throw new LogicException(
            sprintf(
                'The tax engine driver [%s] is flawed. It should be an instanceof `TaxRateResolver`, but it is `%s`',
                $name,
                get_debug_type($instance),
            )
        );
    }
}
