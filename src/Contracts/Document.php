<?php

declare(strict_types=1);

namespace Vanilo\Contracts;

interface Document
{
    public function getNumber(): string;

    /** The human-readable representation, e.g.: "Order no. ABC-123" */
    public function getTitle(): string;

    ///public function getDocumentDate(): \DateTimeInterface;
}
