<?php

declare(strict_types=1);

namespace App\DTO;

class Measure
{
    public function __construct(
        public readonly int $co2,
        public readonly \DateTime $time,
    ) {
    }
}
