<?php

declare(strict_types=1);

/**
 * Contains the MasterProductVariant class.
 *
 * @copyright   Copyright (c) 2022 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2022-08-23
 *
 */

namespace Vanilo\Foundation\Models;

use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Vanilo\Contracts\Buyable;
use Vanilo\Foundation\Traits\LoadsMediaConversionsFromConfig;
use Vanilo\MasterProduct\Models\MasterProductVariant as BaseMasterProductVariant;
use Vanilo\Properties\Traits\HasPropertyValues;
use Vanilo\Support\Traits\BuyableModel;
use Vanilo\Support\Traits\HasImagesFromMediaLibrary;
use Vanilo\Taxes\Contracts\Taxable;
use Vanilo\Taxes\Traits\BelongsToTaxCategory;

class MasterProductVariant extends BaseMasterProductVariant implements Buyable, HasMedia, Taxable
{
    use BelongsToTaxCategory;
    use BuyableModel;
    use HasPropertyValues;
    use HasImagesFromMediaLibrary;
    use InteractsWithMedia;
    use LoadsMediaConversionsFromConfig;

    public function registerMediaConversions(Media $media = null): void
    {
        $this->loadConversionsFromVaniloConfig();
    }
}
