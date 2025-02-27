<?php

namespace Vanilo\Shipment\Models;

enum TimeUnit: string
{
    case SECONDS = 's';
    case MINUTES = 'm';
    case HOURS = 'h';
    case DAYS = 'd';
    case WEEKS = 'w';
}
