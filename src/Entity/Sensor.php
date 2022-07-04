<?php

declare(strict_types=1);

namespace App\Entity;

use App\Enum\SensorLevel;
use App\Repository\SensorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SensorRepository::class)]
class Sensor extends BaseIdModel
{
    #[ORM\Column(type: 'string', enumType: SensorLevel::class)]
    private SensorLevel $status = SensorLevel::OK;

    // only needed for doctrine
    #[ORM\OneToMany(mappedBy: 'sensor', targetEntity: Measurement::class, fetch: 'EXTRA_LAZY', orphanRemoval: true)]
    private Collection $measurements;

    /**
     * @var Collection<int, Alert>
     */
    #[ORM\OneToMany(mappedBy: 'sensor', targetEntity: Alert::class, fetch: 'EXTRA_LAZY', orphanRemoval: true)]
    private Collection $alerts;

    public function __construct()
    {
        $this->measurements = new ArrayCollection();
        $this->alerts       = new ArrayCollection();
    }

    public function getStatus(): ?SensorLevel
    {
        return $this->status;
    }

    public function setStatus(SensorLevel $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getAlerts(): Collection
    {
        return $this->alerts;
    }

    public function mustTriggerAlert(?Measurement $measure1, ?Measurement $measure2, ?Measurement $measure3): bool
    {
        return $measure1?->hasAlertValue() && $measure2?->hasAlertValue() && $measure3?->hasAlertValue();
    }

    public function mustTriggerWarn(?Measurement $measure1, ?Measurement $measure2, ?Measurement $measure3): bool
    {
        return $measure1?->hasAlertValue() || $measure2?->hasAlertValue() || $measure3?->hasAlertValue();
    }
}
