<?php

declare(strict_types=1);

namespace App\DTO;

class Metric
{
    public function __construct(
        public readonly float $avgLast30Days,
        public readonly float $maxLast30Days,
    ) {
    }

    public static function fromArray(false|array $data): self
    {
        $avg = (\is_array($data) && \array_key_exists('avgLast30Days', $data)) ? (float) $data['avgLast30Days'] : 0;
        $max = (\is_array($data) && \array_key_exists('maxLast30Days', $data)) ? (float) $data['maxLast30Days'] : 0;

        return new self($avg, $max);
    }
}
