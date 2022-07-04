<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\AlertRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Ignore;

#[ORM\Entity(repositoryClass: AlertRepository::class)]
/**
 * @SuppressWarnings(PHPMD.ConstantNamingConventions)
 */
class Alert extends BaseIdModel
{
    public const WarnLevel = 2000;

    public function __construct(
        #[ORM\ManyToOne(targetEntity: Sensor::class, inversedBy: 'alerts')]
        #[ORM\JoinColumn(nullable: false)]
        #[Ignore]
        public readonly Sensor $sensor,
        #[ORM\Column(type: 'datetime')]
        public readonly ?\DateTime $startTime,

        #[ORM\Column(type: 'datetime')]
        public readonly ?\DateTime $endTime,

        #[ORM\Column(type: 'integer')]
        public readonly ?int $measurement1,

        #[ORM\Column(type: 'integer')]
        public readonly ?int $measurement2,

        #[ORM\Column(type: 'integer')]
        public readonly ?int $measurement3,
    ) {
    }

    /**
     * @throws \InvalidArgumentException
     */
    public static function createFromMeasurements(Sensor $sensor, ?Measurement $measurement1, ?Measurement $measurement2, ?Measurement $measurement3): self
    {
        if (null === $measurement1 || null === $measurement2 || null === $measurement3)
        {
            throw new \InvalidArgumentException('Three Measurements required to create an alarm');
        }

        return new self(
            $sensor,
            $measurement1->time,
            $measurement3->time,
            $measurement1->co2,
            $measurement2->co2,
            $measurement3->co2
        );
    }
}
