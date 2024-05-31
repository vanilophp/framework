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

final class Get
{
    use HasPrivateLinkTypeBasedConstructor;
    use FindsDesiredLinkGroups;
    use WantsLinksOrGroups;

    public static function __callStatic($name, $arguments)
    {
        return self::the($name);
    }

    public static function the(LinkType|string $type): self
    {
        return new self($type);
    }

    public function of(Model $model): Collection
    {
        $groups = $this->linkGroupsOfModel($model);

        if ('groups' === $this->wants) {
            return $groups;
        }

        $result = collect();
        if ('linkItems' === $this->wants) {
            $groups->each(function ($group) use ($result, $model) {
                if (is_null($group->root_item_id) || $group->rootItem->linkable_id === $model->id) {
                    $result->push(
                        ...$group
                        ->items
                        ->reject(fn ($item) => $item->linkable_id === $model->id)
                    );
                }
            });

            return $result;
        }

        $groups->each(function ($group) use ($result, $model) {
            if (is_null($group->root_item_id) || $group->rootItem->linkable_id === $model->id) {
                $result->push(
                    ...$group
                    ->items
                    ->map
                    ->linkable
                    ->reject(fn ($item) => $item->id === $model->id)
                );
            }
        });

        return $result;
    }
}
