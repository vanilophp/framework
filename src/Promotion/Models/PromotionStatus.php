<?php

declare(strict_types=1);

namespace Vanilo\Promotion\Models;

enum PromotionStatus: string
{
    case Inactive = 'inactive';
    case Active = 'active';
    case Expired = 'expired';
    case Depleted = 'depleted';

    public function label(): string
    {
        return match ($this) {
            self::Inactive => __('Inactive'),
            self::Active => __('Active'),
            self::Expired => __('Expired'),
            self::Depleted => __('Depleted'),
        };
    }
}
