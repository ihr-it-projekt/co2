<?php

declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\Entity\Alert;
use App\Entity\Measurement;
use App\Entity\Sensor;
use App\Tests\Unit\RandomValueGeneratorTrait;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

/**
 * @covers \App\Entity\Measurement
 */
class MeasurementTest extends TestCase
{
    use RandomValueGeneratorTrait;

    public const ValidTimeString = '2022-07-03T18:55:47+00:00';

    /**
     * @dataProvider dbHasAlertValue
     */
    public function testHasAlertValue(Measurement $measurement, bool $expected): void
    {
        self::assertSame($expected, $measurement->hasAlertValue());
    }

    public function dbHasAlertValue()
    {
        $sensor = $this->createMock(Sensor::class);
        $time   = $this->createMock(\DateTime::class);
        yield 'has warn level' => [
            new Measurement($sensor, Alert::WarnLevel, $time),
            true,
        ];

        yield 'is higher then warn level' => [
            new Measurement($sensor, Alert::WarnLevel + $this->getRandomPositiveIntValue(), $time),
            true,
        ];

        yield 'is lower then warn level' => [
            new Measurement($sensor, Alert::WarnLevel - $this->getRandomPositiveIntValue(), $time),
            false,
        ];
    }

    public function testCreateFromSensorAndMeasureRequest(): void
    {
        $sensor  = $this->createMock(Sensor::class);
        $co2     = $this->getRandomIntValue();
        $request = $this->createConfiguredMock(
            Request::class,
            [
                'toArray' => [
                    'time' => self::ValidTimeString,
                    'co2'  => $co2,
                ],
            ]
        );

        $measurement = Measurement::createFromSensorAndMeasureRequest($sensor, $request);

        self::assertSame($sensor, $measurement->sensor);
        self::assertSame($co2, $measurement->co2);
        self::assertEquals(new \DateTime(self::ValidTimeString), $measurement->time);
    }

    /**
     * @dataProvider dpCreateFromSensorAndMeasureRequestInvalidArguments
     */
    public function testCreateFromSensorAndMeasureRequestInvalidArguments(MockObject|Request $request, string $message): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage($message);

        $sensor = $this->createMock(Sensor::class);

        Measurement::createFromSensorAndMeasureRequest($sensor, $request);
    }

    public function dpCreateFromSensorAndMeasureRequestInvalidArguments()
    {
        $co2 = $this->getRandomIntValue();

        yield 'request has no time key' => [
            $this->createConfiguredMock(
                Request::class,
                [
                    'toArray' => [
                        'co2' => $co2,
                    ],
                ]
            ),
            'Time value has wrong format or is not set.',
        ];
        yield 'time is not a string' => [
            $this->createConfiguredMock(
                Request::class,
                [
                    'toArray' => [
                        'time' => $this->getRandomIntValue(),
                        'co2'  => $co2,
                    ],
                ]
            ),
            'Time value has wrong format or is not set.',
        ];
        yield 'has invalid time format' => [
            $this->createConfiguredMock(
                Request::class,
                [
                    'toArray' => [
                        'time' => 'y-m-d H:i:s',
                        'co2'  => $co2,
                    ],
                ]
            ),
            'Time value has wrong format or is not set.',
        ];
        yield 'request has no co2 key' => [
            $this->createConfiguredMock(
                Request::class,
                [
                    'toArray' => [
                        'time' => self::ValidTimeString,
                    ],
                ]
            ),
            'co2 value is wrong or is not set.',
        ];
        yield 'request has no co2 int value' => [
            $this->createConfiguredMock(
                Request::class,
                [
                    'toArray' => [
                        'time' => self::ValidTimeString,
                        'co2'  => $this->getRandomString(),
                    ],
                ]
            ),
            'co2 value is wrong or is not set.',
        ];
    }
}
