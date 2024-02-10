<?php

declare(strict_types=1);

/**
 * Contains the TaxCategoryType class.
 *
 * @copyright   Copyright (c) 2024 Vanilo UG
 * @author      Attila Fulop
 * @license     MIT
 * @since       2024-02-09
 *
 */

namespace Vanilo\Taxes\Models;

use Konekt\Enum\Enum;
use Vanilo\Taxes\Contracts\TaxCategoryType as TaxCategoryTypeContract;

/**
 * @method static self PHYSICAL_GOODS()
 * @method static self DIGITAL_GOODS_AND_SERVICES()
 * @method static self TRANSPORT_SERVICES()
 * @method static self STANDARD_SERVICES()
 * @method static self REAL_ESTATE_SERVICES()
 * @method static self EVENT_RELATED_SERVICES()
 * @method static self TELECOM_SERVICES()
 * @method static self BROADCASTING()
 *
 * @method bool isPhysicalGoods()
 * @method bool isDigitalGoodsAndServices()
 * @method bool isTransportServices()
 * @method bool isStandardServices()
 * @method bool isRealEstateServices()
 * @method bool isEventRelatedServices()
 * @method bool isTelecomServices()
 * @method bool isBroadcasting()
 */
class TaxCategoryType extends Enum implements TaxCategoryTypeContract
{
    public const __DEFAULT = self::PHYSICAL_GOODS;
    public const PHYSICAL_GOODS = 'physical_goods';
    public const DIGITAL_GOODS_AND_SERVICES = 'digital_goods_and_services';
    public const TRANSPORT_SERVICES = 'transport_services';
    public const STANDARD_SERVICES = 'standard_services';
    public const REAL_ESTATE_SERVICES = 'real_estate_services';
    public const EVENT_RELATED_SERVICES = 'event_related_services';
    public const TELECOM_SERVICES = 'telecom_services';
    public const BROADCASTING = 'broadcasting';

    protected static array $labels = [];

    protected static function boot()
    {
        static::$labels = [
            self::PHYSICAL_GOODS => __('Physical Goods'),
            self::DIGITAL_GOODS_AND_SERVICES => __('Digital Goods/Services (E-Services)'),
            self::TRANSPORT_SERVICES => __('Transport Services'),
            self::TELECOM_SERVICES => __('Telecommunications Services'),
            self::BROADCASTING => __('Broadcasting Services'),
            self::STANDARD_SERVICES => __('Standard Services'),
            self::REAL_ESTATE_SERVICES => __('Real Estate Services'),
            self::EVENT_RELATED_SERVICES => __('Event-Related Services'),
        ];
    }
}
