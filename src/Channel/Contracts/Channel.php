<?php

declare(strict_types=1);

/**
 * Contains the Channel interface.
 *
 * @copyright   Copyright (c) 2019 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2019-07-30
 *
 */

namespace Vanilo\Channel\Contracts;

use Vanilo\Contracts\Configurable;
use Vanilo\Contracts\Merchant;

interface Channel extends Configurable
{
    public function getName(): string;

    public function getSlug(): ?string;

    public function getLanguage(): string;

    public function getCurrency(): ?string;

    public function getDomain(): ?string;

    public function getMerchant(): ?Merchant;

    public function getBillingCountries(): array;

    public function getShippingCountries(): array;
}
