<?php

declare(strict_types=1);

namespace App\Tests\Unit\DTO;

use App\DTO\Metric;
use App\Tests\Unit\RandomValueGeneratorTrait;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\DTO\Metric
 */
class MetricTest extends TestCase
{
    use RandomValueGeneratorTrait;

    /**
     * @dataProvider dpCreate
     */
    public function testFromArray(array $data, float $expectedAvg, float $expectedMax): void
    {
        $metric = Metric::fromArray($data);

        self::assertSame($expectedAvg, $metric->avgLast30Days);
        self::assertSame($expectedMax, $metric->maxLast30Days);
    }

    public function dpCreate(): \Generator
    {
        $avg = $this->getRandomFloatValue();
        $max = $this->getRandomFloatValue();
        yield 'has avg days and max days value' => [
            [
                'avgLast30Days' => $avg,
                'maxLast30Days' => $max,
            ],
            $avg,
            $max,
        ];

        yield 'has max days value' => [
            [
                'maxLast30Days' => $max,
            ],
            0,
            $max,
        ];

        yield 'has avg days' => [
            [
                'avgLast30Days' => $avg,
            ],
            $avg,
            0,
        ];

        yield 'empty array' => [
            [],
            0,
            0,
        ];
    }
}
