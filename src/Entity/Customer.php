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
#[Put(validationContext: [])]
#[GetCollection]
#[Post(validationContext: [])]
class Customer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Assert\Uuid]
    private ?int $id = null;

    #[ORM\Column(length: 8)]
    private ?string $zipcode = null;

    #[ORM\Column]
    private ?int $housenumber = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $firstname = null;

    #[ORM\Column(length: 80)]
    private ?string $lastname = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $gender = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $phonenumber = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateOfBirth = null;

    #[ORM\Column(length: 50)]
    private ?string $bankDetails = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $address = null;

    #[ORM\Column(length: 80, nullable: true)]
    private ?string $city = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?CustomerAdvisor $customerAdvisor = null;

    #[ORM\OneToOne(mappedBy: 'Customer', cascade: ['persist', 'remove'])]
    private ?DeviceManager $deviceManager = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $municipality = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $province = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 14, nullable: true)]
    private ?string $latitude = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 14, nullable: true)]
    private ?string $longitude = null;

    public static function loadValidatorMetadata(ClassMetadata $metadata): void
    {
        $metadata->addPropertyConstraint('firstname', new Assert\Length([
            'min' => 1,
            'max' => 50,
            'minMessage' => 'Your first name must be at least {{ limit }} characters long',
            'maxMessage' => 'Your first name cannot be longer than {{ limit }} characters',
        ]));
        $metadata->addPropertyConstraint('lastname', new Assert\Length([
            'min' => 1,
            'max' => 80,
            'minMessage' => 'Your last name must be at least {{ limit }} characters long',
            'maxMessage' => 'Your last name cannot be longer than {{ limit }} characters',
        ]));
        $metadata->addPropertyConstraint('gender', new Assert\Length([
            'min' => 0,
            'max' => 20,
            'minMessage' => 'Your last name must be at least {{ limit }} characters long',
            'maxMessage' => 'Your last name cannot be longer than {{ limit }} characters',
        ]));
        $metadata->addPropertyConstraint('housenumber', new Assert\Length([
            'min' => 1,
            'max' => 5,
            'minMessage' => 'Your last name must be at least {{ limit }} characters long',
            'maxMessage' => 'Your last name cannot be longer than {{ limit }} characters',
        ]));
        $metadata->addPropertyConstraint('phonenumber', new Assert\Length([
            'min' => 0,
            'max' => 20,
            'minMessage' => 'Your last name must be at least {{ limit }} characters long',
            'maxMessage' => 'Your last name cannot be longer than {{ limit }} characters',
        ]));
        $metadata->addPropertyConstraint('bankDetails', new Assert\Length([
            'min' => 1,
            'max' => 50,
            'minMessage' => 'Your last name must be at least {{ limit }} characters long',
            'maxMessage' => 'Your last name cannot be longer than {{ limit }} characters',
        ]));
        $metadata->addPropertyConstraint('address', new Assert\Length([
            'min' => 0,
            'max' => 100,
            'minMessage' => 'Your last name must be at least {{ limit }} characters long',
            'maxMessage' => 'Your last name cannot be longer than {{ limit }} characters',
        ]));
        $metadata->addPropertyConstraint('city', new Assert\Length([
            'min' => 0,
            'max' => 80,
            'minMessage' => 'Your last name must be at least {{ limit }} characters long',
            'maxMessage' => 'Your last name cannot be longer than {{ limit }} characters',
        ]));
        
        $metadata->addPropertyConstraint('dateOfBirth', new Assert\Date());

        $metadata->addPropertyConstraint('zipcode', new NotBlank());
        $metadata->addPropertyConstraint('housenumber', new NotBlank());
        $metadata->addPropertyConstraint('lastname', new NotBlank());
        $metadata->addPropertyConstraint('bankDetails', new NotBlank());
        $metadata->addPropertyConstraint('customerAdvisor', new NotBlank());
        $metadata->addPropertyConstraint('email', new NotBlank());
        $metadata->addPropertyConstraint('email', new Assert\Email([
            'message' => 'The email "{{ value }}" is not a valid email.',
        ]));
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

    public function getMunicipality(): ?string
    {
        return $this->municipality;
    }

    public function setMunicipality(?string $municipality): static
    {
        $this->municipality = $municipality;

        return $this;
    }

    public function getProvince(): ?string
    {
        return $this->province;
    }

    public function setProvince(?string $province): static
    {
        $this->province = $province;

        return $this;
    }

    public function getLatitude(): ?string
    {
        return $this->latitude;
    }

    public function setLatitude(?string $latitude): static
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?string
    {
        return $this->longitude;
    }

    public function setLongitude(?string $longitude): static
    {
        $this->longitude = $longitude;

        return $this;
    }
}
