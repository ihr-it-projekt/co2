<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\MeasurementRepository;
use Doctrine\ORM\Mapping as ORM;
use OpenApi\Attributes as OAT;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Annotation\Ignore;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: MeasurementRepository::class)]
class Measurement
{
    public function __construct(
        #[ORM\ManyToOne(targetEntity: Sensor::class, inversedBy: 'measurements')]
        #[ORM\JoinColumn(nullable: false)]
        #[Ignore]
        public readonly Sensor $sensor,

        #[ORM\Column(type: 'integer')]
        #[OAT\Property()]
        #[Assert\Positive(message: 'co2 must be positive.')]
        #[Assert\NotNull(message: 'co2 can not be null.')]
        public readonly int $co2,

        #[ORM\Column(type: 'datetime')]
        #[OAT\Property()]
        #[Assert\DateTime('Invalid time value.')]
        public readonly \DateTime $time
    ) {
    }

    public function hasAlertValue(): bool
    {
        return Alert::WarnLevel <= $this->co2;
    }

    /**
     * @throws \Exception
     */
    public static function createFromSensorAndMeasureRequest(Sensor $sensor, Request $request): self
    {
        $requestData = $request->toArray();
        $time        = self::validateAndGetTime($requestData);
        $co2         = self::validateAndGetCo2($requestData);

        return new self($sensor, $co2, $time);
    }

    /**
     * @throws \Exception
     */
    private static function validateAndGetTime(array $requestData): \DateTime
    {
        if (!\array_key_exists('time', $requestData)
            || !\is_string($requestData['time'])
        ) {
            throw new \InvalidArgumentException('Time value has wrong format or is not set.');
        }

        $dateTime = \DateTime::createFromFormat(\DateTime::ATOM, $requestData['time']);

        if (!$dateTime)
        {
            throw new \InvalidArgumentException('Time value has wrong format or is not set.');
        }

        return new \DateTime($requestData['time']);
    }

    /**
     * @throws \InvalidArgumentException
     */
    private static function validateAndGetCo2(array $requestData): int
    {
        if (!\array_key_exists('co2', $requestData) || !\is_int($requestData['co2']))
        {
            throw new \InvalidArgumentException('co2 value is wrong or is not set.');
        }

        return $requestData['co2'];
    }
}
