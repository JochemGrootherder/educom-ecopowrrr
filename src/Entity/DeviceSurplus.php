<?php

namespace App\Entity;

use App\Repository\DeviceSurplusRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DeviceSurplusRepository::class)]
class DeviceSurplus
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'deviceSurpluses')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Period $period = null;

    #[ORM\ManyToOne(inversedBy: 'deviceSurpluses')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Device $device = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $amount = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPeriod(): ?Period
    {
        return $this->period;
    }

    public function setPeriod(?Period $period): static
    {
        $this->period = $period;

        return $this;
    }

    public function getDevice(): ?Device
    {
        return $this->device;
    }

    public function setDevice(?Device $device): static
    {
        $this->device = $device;

        return $this;
    }

    public function getAmount(): ?string
    {
        return $this->amount;
    }

    public function setAmount(string $amount): static
    {
        $this->amount = $amount;

        return $this;
    }
}