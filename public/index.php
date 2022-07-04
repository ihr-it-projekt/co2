<?php

declare(strict_types=1);

use App\Kernel;

require_once dirname(__DIR__).'/vendor/autoload_runtime.php';

return function (array $context)
{
    $appEnv   = array_key_exists('APP_ENV', $context) ? (string) $context['APP_ENV'] : 'prod';
    $appDebug = array_key_exists('APP_DEBUG', $context) ? (bool) $context['APP_DEBUG'] : false;

    return new Kernel($appEnv, $appDebug);
};
