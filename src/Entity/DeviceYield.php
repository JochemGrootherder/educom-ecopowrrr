<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\DeviceYieldRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Mapping\ClassMetadata;

#[ORM\Entity(repositoryClass: DeviceYieldRepository::class)]
#[ApiResource]
#[Delete]
#[Get]
#[Put(validationContext: [])]
#[GetCollection]
#[Post(validationContext: [])]
class DeviceYield
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Assert\Uuid]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Period $Period = null;

    #[ORM\ManyToOne(inversedBy: 'deviceYields')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Device $Device = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $amount = null;
    
    public static function loadValidatorMetadata(ClassMetadata $metadata): void
    {
        $metadata->addPropertyConstraint('amount', new Assert\Range([
            'min' => 0,
            'max' => 99999,
        ]));
        $metadata->addPropertyConstraint('Period', new NotBlank());
        $metadata->addPropertyConstraint('device', new NotBlank());
        $metadata->addPropertyConstraint('amount', new NotBlank());
    }

    public function getId(): ?int
    {
        return $this->id;
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
}
