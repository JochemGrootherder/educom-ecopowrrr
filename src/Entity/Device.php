<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\DeviceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints;
use Symfony\Component\Validator\Constraints as Assert;


use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Mapping\ClassMetadata;

#[ORM\Entity(repositoryClass: DeviceRepository::class)]
#[ApiResource]
#[Delete]
#[Get]
#[Put(validationContext: [])]
#[GetCollection]
#[Post(validationContext: [])]
class Device
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Assert\Uuid]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'devices')]
    #[ORM\JoinColumn(nullable: false)]
    private ?DeviceManager $DeviceManager = null;

    #[ORM\Column(length: 80)]
    private ?string $serialNumber = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank()]
    private ?DeviceType $deviceType = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank()]
    private ?DeviceStatus $deviceStatus = null;

    /**
     * @var Collection<int, DeviceYield>
     */
    #[ORM\OneToMany(targetEntity: DeviceYield::class, mappedBy: 'Device', orphanRemoval: true)]
    private Collection $deviceYields;

    public function __construct()
    {
        $this->deviceYields = new ArrayCollection();
        $this->deviceSurpluses = new ArrayCollection();
    }

    public static function loadValidatorMetadata(ClassMetadata $metadata): void
    {
        $metadata->addPropertyConstraint('serialNumber', new Assert\Length([
            'min' => 1,
            'max' => 80,
            'minMessage' => 'Your last name must be at least {{ limit }} characters long',
            'maxMessage' => 'Your last name cannot be longer than {{ limit }} characters',
        ]));
        $metadata->addPropertyConstraint('serialNumber', new NotBlank());
        $metadata->addPropertyConstraint('deviceType', new NotBlank());
        $metadata->addPropertyConstraint('deviceStatus', new NotBlank());
    }

    public function getYieldUntillPeriod($period)
    {
        $totalYield = 0.0;
        foreach($this->deviceYields as $yield)
        {
            $yieldPeriod = $yield->getPeriod();
            $yieldEndDate = $yieldPeriod->getEndDate();
            $periodEndDate = $period->getEndDate();
            if($yieldEndDate <= $periodEndDate)
            {
                $totalYield += $yield->getAmount();
            }
        }
        return $totalYield;
    }

    public function getPeriodYield($period)
    {
        foreach($this->deviceYields as $yield)
        {
            if($yield->getPeriod() === $period)
            {
                return $yield;
            }
        }
        return null;
    }

    public function getYieldByDate($date)
    {
        $date = $date->format('Y-m-d');
        foreach($this->deviceYields as $yield)
        {
            $yieldDate = $yield->getDate()->format('Y-m-d');
            if($yieldDate === $date)
            {
                return $yield;
            }
        }
        return null;
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getSerialNumber(): ?string
    {
        return $this->serialNumber;
    }

    public function setSerialNumber(string $serialNumber): static
    {
        $this->serialNumber = $serialNumber;

        return $this;
    }

    public function getDeviceType(): ?DeviceType
    {
        return $this->deviceType;
    }

    public function setDeviceType(?DeviceType $deviceType): static
    {
        $this->deviceType = $deviceType;

        return $this;
    }

    public function getDeviceStatus(): ?DeviceStatus
    {
        return $this->deviceStatus;
    }

    public function setDeviceStatus(?DeviceStatus $deviceStatus): static
    {
        $this->deviceStatus = $deviceStatus;

        return $this;
    }

    /**
     * @return Collection<int, DeviceYield>
     */
    public function getDeviceYields(): Collection
    {
        return $this->deviceYields;
    }

    public function addDeviceYield(DeviceYield $deviceYield): static
    {
        if (!$this->deviceYields->contains($deviceYield)) {
            $this->deviceYields->add($deviceYield);
            $deviceYield->setDevice($this);
        }
        return $this;
    }

    public function removeDeviceYield(DeviceYield $deviceYield): static
    {
        if ($this->deviceYields->removeElement($deviceYield)) {
            // set the owning side to null (unless already changed)
            if ($deviceYield->getDevice() === $this) {
                $deviceYield->setDevice(null);
            }
        }

        return $this;
    }
}
