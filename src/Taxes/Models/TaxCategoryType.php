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
    public const LOCATION_TIED_SERVICES = 'location_tied_services';
    public const INTANGIBLE_SERVICES = 'intangible_services';
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
            self::LOCATION_TIED_SERVICES => __('Location-Tied Services'),
            self::INTANGIBLE_SERVICES => __('Intangible/Remote Services'),
            self::REAL_ESTATE_SERVICES => __('Real Estate Services'),
            self::EVENT_RELATED_SERVICES => __('Event-Related Services'),
        ];
    }

    public function explanation(): string
    {
        return match ($this->value) {
            self::PHYSICAL_GOODS => __('Tangible items that can be seen, touched, and physically handled. They occupy physical space and can be transferred from one party to another.'),
            self::DIGITAL_GOODS_AND_SERVICES => __('Services or goods that are delivered over the internet or an electronic network which is also inherently "non-location specific."'),
            self::TRANSPORT_SERVICES => __('Activities involved in moving goods or people from one location to another, utilizing various modes of transport. These services encompass all operations necessary for transportation, including logistics, freight handling, and passenger services.'),
            self::TELECOM_SERVICES => __('Electronic transmission of information over distances, including voice, data, text, and video. These services are provided through networks utilizing cables, telephone lines, satellites, or internet protocols.'),
            self::BROADCASTING => __('Distribution of audio, video, or other content to a dispersed audience via mass communication mediums, like radio, television, and digital platforms. These services include both live and pre-recorded content.'),
            self::LOCATION_TIED_SERVICES => __('Services physically carried out or at a specific location, such as catering services consumed on the premises or repairs tied to a tangible asset. These are characterized by the fact that the place of supply, and therefore the Tax treatment, is determined by the location where the service is performed.'),
            self::INTANGIBLE_SERVICES => __('Services where the place of supply is not tied to a physical location, like consulting, legal, advertising or data processing services. Such services can be remote: they can be performed remotely without needing to be at a specific location.'),
            self::REAL_ESTATE_SERVICES => __('Activities related to the selling, buying, leasing, or managing of land and buildings. These services include property management, real estate brokerage, appraisal, and legal services associated with property transactions and management.'),
            self::EVENT_RELATED_SERVICES => __('Admission to cultural, artistic, sporting, scientific, educational, entertainment or similar events, such as fairs and exhibitions; and of ancillary services related to the admission like planning, organizing, and managing of events.'),
        };
    }
}
