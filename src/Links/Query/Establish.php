<?php

declare(strict_types=1);

/**
 * Contains the Establish class.
 *
 * @copyright   Copyright (c) 2022 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2022-02-23
 *
 */

namespace Vanilo\Links\Query;

use Illuminate\Database\Eloquent\Model;
use Vanilo\Links\Contracts\LinkGroup;
use Vanilo\Links\Contracts\LinkType;
use Vanilo\Links\Models\LinkGroupItemProxy;
use Vanilo\Links\Models\LinkGroupProxy;
use Vanilo\Links\Traits\NormalizesLinkType;

final class Establish
{
    use NormalizesLinkType;
    use HasPropertyFilter;
    use FindsDesiredLinkGroups;

    private LinkType $type;

    private string $wants = 'link';

    private Model $baseModel;

    private function __construct(LinkType|string $type)
    {
        $this->type = $this->normalizeLinkTypeModel($type);
    }

    public static function a(LinkType|string $type): self
    {
        return new self($type);
    }

    public static function an(LinkType|string $type): self
    {
        return self::a($type);
    }

    public function link(): self
    {
        $this->wants = 'link';

        return $this;
    }

    public function group(): self
    {
        $this->wants = 'group';

        return $this;
    }

    public function between(Model $model): self
    {
        $this->baseModel = $model;

        return $this;
    }

    public function and(Model ...$models): void
    {
        $groups = $this->linkGroupsOfModel($models[0]);
        $destinationGroup = $groups->first();
        if (null === $destinationGroup) {
            $destinationGroup = $this->createNewLinkGroup();
            LinkGroupItemProxy::create([
                'link_group_id' => $destinationGroup->id,
                'linkable_id' => $this->baseModel->id,
                'linkable_type' => $this->baseModel::class,
            ]);
        }

        foreach ($models as $model) {
            LinkGroupItemProxy::create([
                'link_group_id' => $destinationGroup->id,
                'linkable_id' => $model->id,
                'linkable_type' => $model::class,
            ]);
        }
    }

    private function createNewLinkGroup(): LinkGroup
    {
        return LinkGroupProxy::create([
            'link_type_id' => $this->type->id,
            'property_id' => $this->hasPropertyFilter() ? $this->propertyId() : null,
        ])->fresh();
    }
}
