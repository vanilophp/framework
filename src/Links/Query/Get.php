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

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Vanilo\Links\Contracts\LinkType;
use Vanilo\Links\Models\LinkGroupItemProxy;
use Vanilo\Links\Traits\NormalizesLinkType;

final class Get
{
    use NormalizesLinkType;

    private LinkType $type;

    private null|int|string $property = null;

    private string $wants = 'links';

    private function __construct(LinkType|string $type)
    {
        $this->type = $this->normalizeLinkTypeModel($type);
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

        if (null !== $this->property) {
            // @todo if property is string, resolve the model
            // Import PropertyProxy but before using, check if the class exists,
            // if not, give an error, telling the developer to install the properties package
            $groups = $groups->filter(fn ($group) => $group->property_id == $this->property);
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
}
