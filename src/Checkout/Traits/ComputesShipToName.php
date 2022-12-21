<?php

declare(strict_types=1);

/**
 * Contains the ComputesShipToName trait.
 *
 * @copyright   Copyright (c) 2022 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2022-12-21
 *
 */

namespace Vanilo\Checkout\Traits;

use Vanilo\Contracts\Billpayer;

trait ComputesShipToName
{
    private function getShipToName(Billpayer $billpayer)
    {
        if ($billpayer->isOrganization()) {
            return sprintf(
                '%s (%s)',
                $billpayer->getCompanyName(),
                $billpayer->getFullName()
            );
        }

        return $billpayer->getName();
    }
}
