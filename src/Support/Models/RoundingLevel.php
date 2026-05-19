<?php

declare(strict_types=1);

namespace Vanilo\Support\Models;

enum RoundingLevel: string
{
    case LINE = 'line';
    case AGGREGATE = 'aggregate';
}
