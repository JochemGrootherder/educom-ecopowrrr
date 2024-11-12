<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\DeviceTypeRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Mapping\ClassMetadata;

#[ORM\Entity(repositoryClass: DeviceTypeRepository::class)]
#[ApiResource]
#[Delete]
#[Get]
#[Put(validationContext: [])]
#[GetCollection]
#[Post(validationContext: [])]
class DeviceType
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Assert\Uuid]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $name = null;

    public static function loadValidatorMetadata(ClassMetadata $metadata): void
    {
        $metadata->addPropertyConstraint('name', new Assert\Length([
            'min' => 1,
            'max' => 50,
            'minMessage' => 'Your last name must be at least {{ limit }} characters long',
            'maxMessage' => 'Your last name cannot be longer than {{ limit }} characters',
        ]));
        $metadata->addPropertyConstraint('name', new NotBlank());
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }
}
