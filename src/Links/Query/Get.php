<?php

declare(strict_types=1);

/**
 * Contains the Get class.
 *
 * @copyright   Copyright (c) 2022 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2022-02-19
 *
 */

namespace Vanilo\Links\Query;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Vanilo\Links\Contracts\LinkType;
use Vanilo\Links\Models\LinkGroupItemProxy;
use Vanilo\Links\Traits\NormalizesLinkType;

final class Get
{
    use NormalizesLinkType;

    // Since the properties are an optional dependency, we use it very cautiously
    private const PROPERTY_PROXY_CLASS = '\\Vanilo\\Properties\\Models\\PropertyProxy';

    private static ?string $propertiesModelClass = null;

    private LinkType $type;

    private null|int|string $property = null;

    private string $wants = 'links';

    private function __construct(LinkType|string $type)
    {
        $this->type = $this->normalizeLinkTypeModel($type);
    }

    public static function usePropertiesModel(string $class): void
    {
        self::$propertiesModelClass = $class;
    }

    public static function the(LinkType|string $type): self
    {
        return new self($type);
    }

    public function links(): self
    {
        $this->wants = 'links';

        return $this;
    }

    public function groups(): self
    {
        $this->wants = 'groups';

        return $this;
    }

    public function basedOn(int|string $property): self
    {
        $this->property = $property;

        return $this;
    }

    public function of(Model $model): Collection
    {
        $groups = $model
            ->morphMany(LinkGroupItemProxy::modelClass(), 'linkable')
            ->get()
            ->transform(fn ($item) => $item->group)
            ->filter(fn ($group) => $group->type->id === $this->type->id);

        if ($this->hasPropertyFilter()) {
            $groups = $groups->filter(fn ($group) => $group->property_id == $this->propertyId());
        }

        if ('groups' === $this->wants) {
            return $groups;
        }

        $links = collect();
        $groups->each(function ($group) use ($links, $model) {
            $links->push(
                ...$group
                ->items
                ->map
                ->linkable
                ->reject(fn ($item) => $item->id === $model->id)
            );
        });

        return $links;
    }

    private function propertyId(): ?int
    {
        return match (true) {
            is_null($this->property) => null,
            is_int($this->property) => $this->property,
            is_string($this->property) => $this->fetchProperty()?->id,
            default => null,
        };
    }

    private function hasPropertyFilter(): bool
    {
        return null !== $this->property;
    }

    /**
     * @throws Exception
     * It only works if the Properties module is installed and loaded by Concord
     */
    private function fetchProperty(): ?object
    {
        if (null !== self::$propertiesModelClass) {
            $propertiesClass = self::$propertiesModelClass;
        } else { // Obtain from Concord
            $proxyClass = self::PROPERTY_PROXY_CLASS;
            if (!class_exists($proxyClass)) {
                throw new Exception('The properties module is missing. Use `composer req vanilo/properties` to install it.');
            }
            $propertiesClass = $proxyClass::modelClass();
        }

        return $propertiesClass::findBySlug($this->property);
    }
}
