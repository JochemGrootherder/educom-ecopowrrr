<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\CustomerRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Mapping\ClassMetadata;
//validators
//api controller?

#[ORM\Entity(repositoryClass: CustomerRepository::class)]
#[ApiResource]
#[Delete]
#[Get]
#[Put(validationContext: ['groups' => ['Default', 'putValidation']])]
#[GetCollection]
#[Post(validationContext: ['groups' => ['Default', 'postValidation']])]
class Customer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Assert\Uuid]
    private ?int $id = null;

    #[ORM\Column(length: 8)]
    #[Assert\NotBlank(groups: ['postValidation'])]
    #[Assert\Length(min: 4, max: 8, groups: ['postValidation'])]
    #[Assert\Length(min: 4, max: 8, groups: ['putValidation'])]
    private ?string $zipcode = null;

    #[ORM\Column]
    #[Assert\NotBlank(groups: ['postValidation'])]
    #[Assert\Length(min: 1, max: 5, groups: ['postValidation'])]
    #[Assert\Length(min: 1, max: 5, groups: ['putValidation'])]
    private ?int $housenumber = null;

    #[ORM\Column(length: 50, nullable: true)]
    #[Assert\Length(min: 1, max: 50, groups: ['postValidation'])]
    #[Assert\Length(min: 1, max: 50, groups: ['putValidation'])]
    private ?string $firstname = null;

    #[ORM\Column(length: 80)]
    #[Assert\Length(min: 1, max: 80, groups: ['postValidation'])]
    #[Assert\Length(min: 1, max: 80, groups: ['putValidation'])]
    #[Assert\NotBlank(groups: ['postValidation'])]
    private ?string $lastname = null;

    #[ORM\Column(length: 20, nullable: true)]
    #[Assert\Length(min: 0, max: 20, groups: ['postValidation'])]
    #[Assert\Length(min: 0, max: 20, groups: ['putValidation'])]
    private ?string $gender = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(groups: ['postValidation'])]
    #[Assert\Length(min: 5, max: 255, groups: ['postValidation'])]
    #[Assert\Length(min: 5, max: 255, groups: ['putValidation'])]
    private ?string $email = null;

    #[ORM\Column(length: 20, nullable: true)]
    #[Assert\Length(min: 0, max: 20, groups: ['putValidation'])]
    #[Assert\Length(min: 0, max: 20, groups: ['postValidation'])]
    private ?string $phonenumber = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    #[Assert\Length(min: 10, max: 10, groups: ['postValidation'])]
    #[Assert\Length(min: 10, max: 10, groups: ['putValidation'])]
    private ?\DateTimeInterface $dateOfBirth = null;

    #[ORM\Column(length: 50)]
    #[Assert\Length(min: 0, max: 50, groups: ['postValidation'])]
    #[Assert\Length(min: 0, max: 50, groups: ['putValidation'])]
    #[Assert\NotBlank(groups: ['postValidation'])]
    private ?string $bankDetails = null;

    #[ORM\Column(length: 100, nullable: true)]
    #[Assert\Length(min: 0, max: 100, groups: ['postValidation'])]
    #[Assert\Length(min: 0, max: 100, groups: ['putValidation'])]
    private ?string $address = null;

    #[ORM\Column(length: 80, nullable: true)]
    #[Assert\Length(min: 0, max: 80, groups: ['postValidation'])]
    #[Assert\Length(min: 0, max: 80, groups: ['putValidation'])]
    private ?string $city = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank(groups: ['postValidation'])]
    private ?CustomerAdvisor $customerAdvisor = null;

    #[ORM\OneToOne(mappedBy: 'Customer', cascade: ['persist', 'remove'])]
    private ?DeviceManager $deviceManager = null;

    public static function loadValidatorMetadata(ClassMetadata $metadata) : void
    {
        $metadata->addPropertyConstraint('zipcode', new NotBlank());
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getZipcode(): ?string
    {
        return $this->zipcode;
    }

    public function setZipcode(string $zipcode): static
    {
        $this->zipcode = $zipcode;

        return $this;
    }

    public function getCustomerAdvisor(): ?CustomerAdvisor
    {
        return $this->customerAdvisor;
    }

    public function setCustomerAdvisor(?CustomerAdvisor $customerAdvisor): static
    {
        $this->customerAdvisor = $customerAdvisor;

        return $this;
    }

    public function getHousenumber(): ?int
    {
        return $this->housenumber;
    }

    public function setHousenumber(int $housenumber): static
    {
        $this->housenumber = $housenumber;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(?string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(?string $gender): static
    {
        $this->gender = $gender;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPhonenumber(): ?string
    {
        return $this->phonenumber;
    }

    public function setPhonenumber(?string $phonenumber): static
    {
        $this->phonenumber = $phonenumber;

        return $this;
    }

    public function getDateOfBirth(): ?\DateTimeInterface
    {
        return $this->dateOfBirth;
    }

    public function setDateOfBirth(?\DateTimeInterface $dateOfBirth): static
    {
        $this->dateOfBirth = $dateOfBirth;

        return $this;
    }

    public function getBankDetails(): ?string
    {
        return $this->bankDetails;
    }

    public function setBankDetails(string $bankDetails): static
    {
        $this->bankDetails = $bankDetails;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getDeviceManager(): ?DeviceManager
    {
        return $this->deviceManager;
    }

    public function setDeviceManager(DeviceManager $deviceManager): static
    {
        // set the owning side of the relation if necessary
        if ($deviceManager->getCustomer() !== $this) {
            $deviceManager->setCustomer($this);
        }

        $this->deviceManager = $deviceManager;

        return $this;
    }
}
