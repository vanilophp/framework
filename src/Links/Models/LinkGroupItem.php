<?php

declare(strict_types=1);

/**
 * Contains the LinkGroupItem class.
 *
 * @copyright   Copyright (c) 2022 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2022-02-16
 *
 */

namespace Vanilo\Links\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;
use Vanilo\Links\Contracts\LinkGroupItem as LinkGroupItemContract;

/**
 * @property int $id
 * @property int $link_group_id
 * @property int $linkable_id
 * @property string $linkable_type
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 *
 * @property-read LinkGroup $group
 *
 * @method static LinkGroupItem create(array $attributes)
 */
class LinkGroupItem extends Model implements LinkGroupItemContract
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function group(): BelongsTo
    {
        return $this->belongsTo(LinkGroupProxy::modelClass(), 'link_group_id');
    }

    public function linkable(): MorphTo
    {
        return $this->morphTo();
    }
}
