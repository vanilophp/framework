<?php

declare(strict_types=1);

/**
 * Contains the Channel class.
 *
 * @copyright   Copyright (c) 2019 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2019-07-30
 *
 */

namespace Vanilo\Channel\Models;

use Carbon\Carbon;
use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Arr;
use Konekt\Address\Contracts\Zone;
use Konekt\Address\Models\ZoneProxy;
use Vanilo\Channel\Contracts\Channel as ChannelContract;
use Vanilo\Contracts\Merchant;
use Vanilo\Contracts\Schematized;
use Vanilo\Support\Dto\Address;
use Vanilo\Support\Traits\ConfigurableModel;

/**
 * @property int $id
 * @property string $name
 * @property ?string $slug
 * @property ?string $currency
 * @property ?array $configuration
 * @property ?string $language
 * @property ?string $domain
 * @property ?string $billing_company
 * @property ?string $billing_country_id
 * @property ?string $billing_province_id
 * @property ?string $billing_postalcode
 * @property ?string $billing_city
 * @property ?string $billing_address
 * @property ?string $billing_address2
 * @property ?string $billing_tax_nr
 * @property ?string $billing_registration_nr
 * @property ?string $email
 * @property ?string $phone
 * @property ?int $billing_zone_id
 * @property ?int $shipping_zone_id
 * @property string color
 * @property ?string theme
 *
 * @property ?Zone $billingZone
 * @property ?Zone $shippingZone
 *
 * @property ?Carbon $deleted_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @method static Channel create(array $attributes = [])
 */
class Channel extends Model implements ChannelContract
{
    use ConfigurableModel;
    use Sluggable;
    use SluggableScopeHelpers;

    protected $table = 'channels';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $casts = [
        'configuration' => 'array',
    ];

    public static function findByDomain(string $domain): ?Channel
    {
        return self::query()->where('domain', $domain)->first();
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function getConfig(string $key, mixed $default = null): mixed
    {
        if (!is_array($this->configuration)) {
            return $default;
        }

        return Arr::get($this->configuration, $key, $default);
    }

    public function setConfig(string $key, mixed $value): void
    {
        $config = $this->configuration ?? [];

        Arr::set($config, $key, $value);

        $this->configuration = $config;
    }

    public function getLanguage(): string
    {
        return $this->language;
    }

    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    public function getDomain(): ?string
    {
        return $this->domain;
    }

    public function getMerchant(): ?Merchant
    {
        return new \Vanilo\Support\Dto\Merchant(
            $this->billing_company,
            new Address(
                $this->billing_company,
                $this->billing_country_id,
                $this->billing_address,
                $this->billing_city,
                $this->billing_postalcode,
                $this->billing_address2,
            ),
            $this->billing_tax_nr,
            $this->billing_registration_nr,
            $this->email,
            $this->phone,
            $this->name,
        );
    }

    public function getBillingCountries(): array
    {
        if (!$this->zoneSupportIsPresent() || null === $this->billingZone) {
            return [];
        }

        return $this->billingZone->getMemberCountryIds();
    }

    public function getShippingCountries(): array
    {
        if (!$this->zoneSupportIsPresent() || null === $this->shippingZone) {
            return [];
        }

        return $this->shippingZone->getMemberCountryIds();
    }

    public function billingZone(): ?BelongsTo
    {
        if ($this->zoneSupportIsPresent()) {
            return $this->belongsTo(ZoneProxy::modelClass(), 'billing_zone_id', 'id');
        }

        return null;
    }

    public function shippingZone(): ?BelongsTo
    {
        if ($this->zoneSupportIsPresent()) {
            return $this->belongsTo(ZoneProxy::modelClass(), 'shipping_zone_id', 'id');
        }

        return null;
    }

    public function getConfigurationSchema(): ?Schematized
    {
        return null;
    }

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    protected function zoneSupportIsPresent(): bool
    {
        return class_exists(ZoneProxy::class);
    }
}
