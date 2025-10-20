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

namespace Vanilo\Taxes\Drivers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Str;
use InvalidArgumentException;
use LogicException;
use ReflectionMethod;
use Vanilo\Contracts\Address;
use Vanilo\Contracts\Billpayer;
use Vanilo\Taxes\Contracts\Taxable;
use Vanilo\Taxes\Contracts\TaxEngineDriver;
use Vanilo\Taxes\Contracts\TaxRate;
use Vanilo\Taxes\Exceptions\InvalidTaxConfigurationException;

/** @todo Check in v6 if this class can be made a Registry from konekt/xtend */
class TaxEngineManager
{
    public const NULL_DRIVER = 'none';
    public const SIMPLE_DRIVER = 'simple';

    /** The array of registered Tax Resolver drivers */
    protected static array $drivers = [
        self::NULL_DRIVER => NullTaxEngineDriver::class,
        self::SIMPLE_DRIVER => SimpleTaxEngineDriver::class
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

    public static function getDrivers(): array
    {
        return self::$drivers;
    }

    public static function ids(): array
    {
        return array_keys(self::$drivers);
    }

    public static function choices(): array
    {
        return array_map(function ($driver) {
            return match (method_exists($driver, 'getName') && (new ReflectionMethod($driver, 'getName'))->isStatic()) {
                true => $driver::getName(),
                default => ucwords(Str::replace('_', ' ', Str::snake(Str::replaceLast('Driver', '', class_basename($driver))))),
            };
        }, self::$drivers);
    }

    public function driverExists(string $driverName): bool
    {
        return isset(self::$drivers[$driverName]);
    }

    public function driver(?string $name = null): TaxEngineDriver
    {
        $name = $name ?: $this->getDefaultDriver();

        return $this->instances[$name] ??= $this->resolve($name);
    }

    public function extend(string $name, string|callable $driver): void
    {
        if (is_callable($driver) || (is_string($driver) && is_subclass_of($driver, TaxEngineDriver::class))) {
            self::$drivers[$name] = $driver;
            if (isset($this->instances[$name])) {
                unset($this->instances[$name]);
            }

            return;
        }

        throw new InvalidArgumentException("Can not register the [{$name}] Tax engine driver, since the passed driver is neither callable, nor a `TaxRateResolver` class");
    }

    public function resolveTaxRate(Taxable $taxable, ?Billpayer $billpayer = null, ?Address $shippingAddress = null): ?TaxRate
    {
        return $this->driver()->resolveTaxRate($taxable, $billpayer, $shippingAddress);
    }

    protected function getDefaultDriver(): string
    {
        return $this->app['config']['vanilo.taxes.engine.driver'] ?? self::NULL_DRIVER;
    }

    protected function resolve(string $name): TaxEngineDriver
    {
        if (null === $driver = self::$drivers[$name]) {
            throw new InvalidTaxConfigurationException("The tax engine driver [{$name}] is not defined.");
        } elseif (self::NULL_DRIVER === $driver) {
            return new NullTaxEngineDriver();
        }

        $instance = match (true) {
            is_string($driver) && class_exists($driver) => $this->app->make($driver),
            is_callable($driver) => call_user_func($driver, $this->app['config']["vanilo.taxes.engine.{$name}"] ?? []),
        };

        if ($instance instanceof TaxEngineDriver) {
            return $instance;
        }

        throw new LogicException(
            sprintf(
                'The tax engine driver [%s] is flawed. It should be an instanceof `TaxEngineDriver`, but it is `%s`',
                $name,
                get_debug_type($instance),
            )
        );
    }
}
