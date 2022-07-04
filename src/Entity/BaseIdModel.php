<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

class BaseIdModel
{
    /**
     * @psalm-suppress PropertyNotSetInConstructor id will set automatically via doctrine
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    protected ?int $id;

    public function getId(): ?int
    {
        return $this->id;
    }
}
