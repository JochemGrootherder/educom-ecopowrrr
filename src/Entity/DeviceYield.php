<?php

namespace App\Entity;

use App\Repository\DeviceYieldRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: DeviceYieldRepository::class)]
class DeviceYield
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'deviceYields')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Device $Device = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $amount = null;

    #[ORM\ManyToOne(inversedBy: 'deviceYields')]
    private ?Period $Period = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDevice(): ?Device
    {
        return $this->Device;
    }

    public function setDevice(?Device $Device): static
    {
        $this->Device = $Device;

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
