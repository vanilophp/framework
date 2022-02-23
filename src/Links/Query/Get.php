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
use Vanilo\Links\Traits\NormalizesLinkType;

final class Get
{
    use NormalizesLinkType;
    use HasPropertyFilter;
    use FindsDesiredLinkGroups;

    private LinkType $type;

    private string $wants = 'links';

    private function __construct(LinkType|string $type)
    {
        $this->type = $this->normalizeLinkTypeModel($type);
    }

    public static function __callStatic($name, $arguments)
    {
        return self::the($name);
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

    public function of(Model $model): Collection
    {
        $groups = $this->linkGroupsOfModel($model);

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
