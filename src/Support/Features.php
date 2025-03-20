<?php

declare(strict_types=1);

/**
 * Contains the Features class.
 *
 * @copyright   Copyright (c) 2023 Vanilo UG
 * @author      Attila Fulop
 * @license     MIT
 * @since       2023-09-07
 *
 */

namespace Vanilo\Support;

use Illuminate\Support\Str;
use Vanilo\Contracts\Feature;
use Vanilo\Support\Features\Inventory;
use Vanilo\Support\Features\MultiChannel;
use Vanilo\Support\Features\Pricing;
use Vanilo\Support\Features\SearchEngine;

class Features
{
    protected static array $registry = [];

    private static ?MultiChannel $multiChannel = null;

    private static ?Pricing $pricing = null;

    private static ?SearchEngine $searchEngine = null;

    private static ?Inventory $inventory = null;

    public static function findByName(string $name): ?Feature
    {
        return match ($name) {
            'pricing' => self::pricing(),
            'multichannel' => self::multichannel(),
            'search_engine' => self::searchEngine(),
            'inventory' => self::inventory(),
            default => self::instanceByName($name),
        };
    }

    public static function extend(string $class, ?string $name = null): void
    {
        if (!is_subclass_of($class, Feature::class)) {
            throw new \InvalidArgumentException(
                sprintf('The class you are trying to register (%s) must implement the Feature interface.', $class)
            );
        }

        $name ??= Str::camel(class_basename($class));

        self::$registry[$name] = [
            'class' => $class,
            'instance' => null,
        ];
    }

    public static function isEnabled(string $name): bool
    {
        return self::findByName($name)?->isEnabled() ?? false;
    }

    public static function isDisabled(string $name): bool
    {
        return !self::isEnabled($name);
    }

    public static function multichannel(): MultiChannel
    {
        return self::$multiChannel ??= new MultiChannel();
    }

    public static function pricing(): Pricing
    {
        return self::$pricing ??= new Pricing();
    }

    public static function inventory(): Inventory
    {
        return self::$inventory ??= new Inventory();
    }

    public static function searchEngine(): SearchEngine
    {
        return self::$searchEngine ??= new SearchEngine();
    }

    public static function isMultiChannelEnabled(): bool
    {
        return self::multichannel()->isEnabled();
    }

    public static function isMultiChannelDisabled(): bool
    {
        return self::multichannel()->isDisabled();
    }

    public static function isPricingEnabled(): bool
    {
        return self::pricing()->isEnabled();
    }

    public static function isPricingDisabled(): bool
    {
        return self::pricing()->isDisabled();
    }

    public static function isSearchEngineEnabled(): bool
    {
        return self::searchEngine()->isEnabled();
    }

    public static function isSearchEngineDisabled(): bool
    {
        return self::searchEngine()->isDisabled();
    }

    public static function isInventoryEnabled(): bool
    {
        return self::inventory()->isEnabled();
    }

    public static function isInventoryDisabled(): bool
    {
        return self::inventory()->isDisabled();
    }

    private static function instanceByName(string $name): ?Feature
    {
        if (null === $feat = self::$registry[$name] ?? null) {
            return null;
        }

        return self::$registry[$name]['instance'] ??= new self::$registry[$name]['class'];
    }
}
