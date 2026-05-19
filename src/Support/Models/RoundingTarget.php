<?php

declare(strict_types=1);

namespace Vanilo\Support\Models;

enum RoundingTarget: string
{
    case Any = '*';
    case Adjustment = 'adjustment';
}
