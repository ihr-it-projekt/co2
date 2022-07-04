<?php

declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\Entity\Alert;
use App\Entity\Measurement;
use App\Entity\Sensor;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\Entity\Alert;
 */
class AlertTest extends TestCase
{
    public function testCreateFromMeasurements(): void
    {
        $sensor    = $this->createMock(Sensor::class);
        $dateTime1 = $this->createMock(\DateTime::class);
        $dateTime2 = $this->createMock(\DateTime::class);
        $dateTime3 = $this->createMock(\DateTime::class);

        $measurement1 = new Measurement($sensor, 1, $dateTime1);
        $measurement2 = new Measurement($sensor, 2, $dateTime2);
        $measurement3 = new Measurement($sensor, 3, $dateTime3);

        $alert = Alert::createFromMeasurements($sensor, $measurement1, $measurement2, $measurement3);

        self::assertSame($sensor, $alert->sensor);
        self::assertSame($dateTime1, $alert->startTime);
        self::assertSame($dateTime3, $alert->endTime);
        self::assertSame(1, $alert->measurement1);
        self::assertSame(2, $alert->measurement2);
        self::assertSame(3, $alert->measurement3);
    }

    /**
     * @dataProvider dpCreateFromMeasurementsInvalidArgument
     */
    public function testCreateFromMeasurementsInvalidArgument(Sensor $sensor, ?Measurement $measurement1, ?Measurement $measurement2, ?Measurement $measurement3): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Three Measurements required to create an alarm');

        Alert::createFromMeasurements($sensor, $measurement1, $measurement2, $measurement3);
    }

    public function dpCreateFromMeasurementsInvalidArgument()
    {
        $sensor   = $this->createMock(Sensor::class);
        $dateTime = $this->createMock(\DateTime::class);

        $measurement = new Measurement($sensor, 1, $dateTime);

        yield 'measurement 1 is null' => [
            $sensor,
             null,
            $measurement,
            $measurement,
        ];
        yield 'measurement 2 is null' => [
            $sensor,
            $measurement,
            null,
            $measurement,
        ];
        yield 'measurement 3 is null' => [
            $sensor,
            $measurement,
            $measurement,
            null,
        ];
    }
}
