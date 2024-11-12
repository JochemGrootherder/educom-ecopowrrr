<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\CustomerAdvisorRepository;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Mapping\ClassMetadata;

#[ORM\Entity(repositoryClass: CustomerAdvisorRepository::class)]
#[ApiResource]
#[Delete]
#[Get]
#[Put(validationContext: [])]
#[GetCollection]
#[Post(validationContext: [])]
class CustomerAdvisor
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Assert\Uuid]
    private ?int $id = null;

    #[ORM\Column(length: 120)]
    private ?string $username = null;

    #[ORM\Column(length: 120)]
    #[Assert\PasswordStrength([
        'minScore' => Assert\PasswordStrength::STRENGTH_MEDIUM, // Very strong password required
    ])]
    private ?string $password = null;

    public static function loadValidatorMetadata(ClassMetadata $metadata): void
    {
        $metadata->addPropertyConstraint('username', new Assert\Length([
            'min' => 1,
            'max' => 120,
            'minMessage' => 'Your last name must be at least {{ limit }} characters long',
            'maxMessage' => 'Your last name cannot be longer than {{ limit }} characters',
        ]));
        $metadata->addPropertyConstraint('username', new NotBlank());
        /*$metadata->addPropertyConstraint('username', new Assert\Unique([
            'fields' => ['username'],
        ]));*/
        $metadata->addPropertyConstraint('password', new NotBlank());
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }
}
