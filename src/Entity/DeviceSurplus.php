<?php

namespace App\Entity;

use App\Repository\DeviceSurplusRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: DeviceSurplusRepository::class)]
class DeviceSurplus
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $amount = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\ManyToOne(inversedBy: 'surpluses')]
    #[ORM\JoinColumn(nullable: false)]
    private ?DeviceManager $DeviceManager = null;

    #[ORM\ManyToOne(inversedBy: 'deviceSurpluses')]
    private ?Period $Period = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getDeviceManager(): ?DeviceManager
    {
        return $this->DeviceManager;
    }

    public function setDeviceManager(?DeviceManager $DeviceManager): static
    {
        $this->DeviceManager = $DeviceManager;

        return $this;
    }

    public function getPeriod(): ?Period
    {
        return $this->Period;
    }

    public function setPeriod(?Period $Period): static
    {
        $this->Period = $Period;

        return $this;
    }
}
