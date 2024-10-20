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

use Vanilo\Contracts\Feature;
use Vanilo\Support\Features\Inventory;
use Vanilo\Support\Features\MultiChannel;
use Vanilo\Support\Features\Pricing;
use Vanilo\Support\Features\SearchEngine;

class Features
{
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
            default => null,
        };
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
}
