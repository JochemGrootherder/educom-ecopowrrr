<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\DeviceSurplusRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Mapping\ClassMetadata;

#[ORM\Entity(repositoryClass: DeviceSurplusRepository::class)]
#[ApiResource]
#[Delete]
#[Get]
#[Put(validationContext: [])]
#[GetCollection]
#[Post(validationContext: [])]
class DeviceSurplus
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Assert\Uuid]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Period $period = null;

    #[ORM\ManyToOne(inversedBy: 'deviceSurpluses')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Device $device = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $amount = null;

    public static function loadValidatorMetadata(ClassMetadata $metadata): void
    {
        $metadata->addPropertyConstraint('amount', new Assert\Range([
            'min' => -99999,
            'max' => 99999,
        ]));
        $metadata->addPropertyConstraint('period', new NotBlank());
        $metadata->addPropertyConstraint('device', new NotBlank());
        $metadata->addPropertyConstraint('amount', new NotBlank());
    }

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
