<?php

namespace App\Entity;

use App\Repository\DeviceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DeviceRepository::class)]
class Device
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'devices')]
    #[ORM\JoinColumn(nullable: false)]
    private ?DeviceManager $device_manager = null;

    #[ORM\Column]
    private ?string $serial_number = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?DeviceType $device_type = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?DeviceStatus $status = null;

    /**
     * @var Collection<int, DeviceYields>
     */
    #[ORM\OneToMany(targetEntity: DeviceYield::class, mappedBy: 'device')]
    private Collection $deviceYields;

    /**
     * @var Collection<int, DeviceSurplus>
     */
    #[ORM\OneToMany(targetEntity: DeviceSurplus::class, mappedBy: 'device')]
    private Collection $deviceSurpluses;

    public function __construct()
    {
        $this->deviceSurpluses = new ArrayCollection();
        $this->deviceYields = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDeviceManager(): ?DeviceManager
    {
        return $this->device_manager;
    }

    public function setDeviceManager(?DeviceManager $device_manager): static
    {
        $this->device_manager = $device_manager;

        return $this;
    }

    public function getSerialNumber(): ?string
    {
        return $this->serial_number;
    }

    public function setSerialNumber(string $serial_number): static
    {
        $this->serial_number = $serial_number;

        return $this;
    }

    public function getDeviceType(): ?DeviceType
    {
        return $this->device_type;
    }

    public function setDeviceType(?DeviceType $device_type): static
    {
        $this->device_type = $device_type;

        return $this;
    }

    public function getStatus(): ?DeviceStatus
    {
        return $this->status;
    }

    public function setStatus(?DeviceStatus $status): static
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return Collection<int, DeviceSurplus>
     */
    public function getDeviceYields(): Collection
    {
        return $this->DeviceYields;
    }

    public function addDeviceYield(DeviceYield $deviceYield): static
    {
        if (!$this->deviceYields->contains($deviceYield)) {
            $this->deviceYields->add($deviceYield);
            $deviceYields->setDevice($this);
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

    /**
     * @return Collection<int, DeviceSurplus>
     */
    public function getDeviceSurpluses(): Collection
    {
        return $this->deviceSurpluses;
    }

    public function addDeviceSurplus(DeviceSurplus $deviceSurplus): static
    {
        if (!$this->deviceSurpluses->contains($deviceSurplus)) {
            $this->deviceSurpluses->add($deviceSurplus);
            $deviceSurplus->setDevice($this);
        }

        return $this;
    }

    public function removeDeviceSurplus(DeviceSurplus $deviceSurplus): static
    {
        if ($this->deviceSurpluses->removeElement($deviceSurplus)) {
            // set the owning side to null (unless already changed)
            if ($deviceSurplus->getDevice() === $this) {
                $deviceSurplus->setDevice(null);
            }
        }

        return $this;
    }
}
