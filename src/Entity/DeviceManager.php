<?php

namespace App\Entity;

use App\Repository\DeviceManagerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DeviceManagerRepository::class)]
class DeviceManager
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var Collection<int, Device>
     */
    #[ORM\OneToMany(targetEntity: Device::class, mappedBy: 'device_manager', orphanRemoval: true)]
    private Collection $devices;

    #[ORM\OneToOne(mappedBy: 'device_manager', cascade: ['persist', 'remove'])]
    private ?Customer $customer = null;

    /**
     * @var Collection<int, Message>
     */
    #[ORM\OneToMany(targetEntity: Message::class, mappedBy: 'device_manager')]
    private Collection $messages;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?DeviceStatus $device_status = null;

    public function __construct()
    {
        $this->devices = new ArrayCollection();
        $this->messages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Device>
     */
    public function getDevices(): Collection
    {
        return $this->devices;
    }

    public function addDevice(Device $device): static
    {
        if (!$this->devices->contains($device)) {
            $this->devices->add($device);
            $device->setDeviceManager($this);
        }

        return $this;
    }

    public function removeDevice(Device $device): static
    {
        if ($this->devices->removeElement($device)) {
            // set the owning side to null (unless already changed)
            if ($device->getDeviceManager() === $this) {
                $device->setDeviceManager(null);
            }
        }

        return $this;
    }

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(Customer $customer): static
    {
        // set the owning side of the relation if necessary
        if ($customer->getDeviceManager() !== $this) {
            $customer->setDeviceManager($this);
        }

        $this->customer = $customer;

        return $this;
    }

    /**
     * @return Collection<int, Message>
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Message $message): static
    {
        if (!$this->messages->contains($message)) {
            $this->messages->add($message);
            $message->setDeviceManager($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): static
    {
        if ($this->messages->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getDeviceManager() === $this) {
                $message->setDeviceManager(null);
            }
        }

        return $this;
    }

    public function getDeviceStatus(): ?DeviceStatus
    {
        return $this->device_status;
    }

    public function setDeviceStatus(DeviceStatus $device_status): static
    {
        $this->device_status = $device_status;

        return $this;
    }
}
