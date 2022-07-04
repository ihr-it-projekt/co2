<?php

declare(strict_types=1);

namespace App\Enum;

enum SensorLevel: string
{
    case OK    = 'OK';
    case Warn  = 'WARN';
    case Alert = 'ALERT';
}
