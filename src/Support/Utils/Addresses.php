<?php

declare(strict_types=1);

/**
 * Contains the Addresses class.
 *
 * @copyright   Copyright (c) 2023 Vanilo UG
 * @author      Attila Fulop
 * @license     MIT
 * @since       2023-02-16
 *
 */

namespace Vanilo\Support\Utils;

use Vanilo\Contracts\Address;

class Addresses
{
    public static function are(Address $address1, Address $address2): AddressComparisonResult
    {
        return new AddressComparisonResult(
            self::textsDiffer($address1->getName(), $address2->getName()),
            self::textsDiffer($address1->getCountryCode(), $address2->getCountryCode()),
            self::textsDiffer($address1->getProvinceCode(), $address2->getProvinceCode()),
            self::textsDiffer($address1->getPostalCode(), $address2->getPostalCode()),
            self::textsDiffer($address1->getCity(), $address2->getCity()),
            self::textsDiffer($address1->getAddress(), $address2->getAddress()),
        );
    }

    protected static function textsDiffer(?string $s1, ?string $s2): bool
    {
        if (null === $s1 || null === $s2) {
            return $s1 !== $s2;
        }

        $n1 = str_replace('  ', ' ', str_replace('  ', ' ', str_replace('   ', ' ', str_replace('    ', ' ', strtolower(trim($s1))))));
        $n2 = str_replace('  ', ' ', str_replace('  ', ' ', str_replace('   ', ' ', str_replace('    ', ' ', strtolower(trim($s2))))));

        return $n1 !== $n2;
    }
}
