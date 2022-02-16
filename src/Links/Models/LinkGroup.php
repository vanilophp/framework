<?php

declare(strict_types=1);

/**
 * Contains the LinkGroup class.
 *
 * @copyright   Copyright (c) 2022 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2022-02-16
 *
 */

namespace Vanilo\Links\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Vanilo\Links\Contracts\LinkGroup as LinkGroupContract;

/**
 *
 * @property int $id
 * @property int $link_type_id
 * @property int|null $property_id
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 *
 * @property-read LinkType $linkType
 */
class LinkGroup extends Model implements LinkGroupContract
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function linkType(): BelongsTo
    {
        return $this->belongsTo(LinkTypeProxy::modelClass());
    }
}
