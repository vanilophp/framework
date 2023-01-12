<?php

declare(strict_types=1);

/**
 * Contains the ConfigurableModel trait.
 *
 * @copyright   Copyright (c) 2023 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2023-01-10
 *
 */

namespace Vanilo\Support\Traits;

trait ConfigurableModel
{
    protected static string $configurationFieldName = 'configuration';

    public static function bootConfigurableModel()
    {
        static::saving(function ($model) {
            if (null === $model->{static::$configurationFieldName}) {
                $model->{static::$configurationFieldName} = [];
            }
        });
    }

    public function configuration(): ?array
    {
        return $this->{static::$configurationFieldName};
    }

    public function hasConfiguration(): bool
    {
        return null !== $this->{static::$configurationFieldName};
    }

    public function doesntHaveConfiguration(): bool
    {
        return !$this->hasConfiguration();
    }
}
