<?php

declare(strict_types=1);

namespace App\Tests\Unit;

trait RandomValueGeneratorTrait
{
    public function getRandomIntValue(): int
    {
        return random_int(\PHP_INT_MIN, \PHP_INT_MAX);
    }

    public function getRandomPositiveIntValue(): int
    {
        return random_int(1, \PHP_INT_MAX);
    }

    public function getRandomFloatValue(): float
    {
        return random_int(\PHP_INT_MIN, \PHP_INT_MAX) / 10;
    }

    public function getRandomString($length = 10): string
    {
        return substr(
            str_shuffle(
                str_repeat(
                    $x = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',
                    (int) ceil(
                        $length / \strlen($x)
                    )
                )
            ),
            1,
            $length
        );
    }
}
