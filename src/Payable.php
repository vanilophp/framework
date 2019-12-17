<?php
/**
 * Contains the Payable interface.
 *
 * @copyright   Copyright (c) 2019 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2019-12-17
 *
 */

namespace Vanilo\Contracts;

interface Payable
{
    public function getAmount();

    public function getCurrency(): string;

    public function getType(): string;

    public function getId(): int;
}
