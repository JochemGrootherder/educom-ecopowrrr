<?php

namespace App\Entity;

use App\Repository\DeviceManagerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Mapping\ClassMetadata;

use App\Message\MessageContent;

#[ORM\Entity(repositoryClass: DeviceManagerRepository::class)]
class DeviceManager
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?DeviceStatus $status = null;

    /**
     * @var Collection<int, Device>
     */
    #[ORM\OneToMany(targetEntity: Device::class, mappedBy: 'DeviceManager')]
    private Collection $devices;

    #[ORM\OneToOne(inversedBy: 'deviceManager', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Customer $Customer = null;

    /**
     * @var Collection<int, DeviceSurplus>
     */
    #[ORM\OneToMany(targetEntity: DeviceSurplus::class, mappedBy: 'DeviceManager')]
    private Collection $surpluses;

    public function __construct()
    {
        $this->devices = new ArrayCollection();
        $this->surpluses = new ArrayCollection();
    }

    public static function loadValidatorMetadata(ClassMetadata $metadata): void
    {
        $metadata->addPropertyConstraint('status', new NotBlank());
        $metadata->addPropertyConstraint('Customer', new NotBlank());
    }

    public function getSurplusUntillPeriod($period)
    {
        $totalSurplus = 0.0;
        foreach($this->surpluses as $surplus)
        {
            $surplusPeriod = $surplus->getPeriod();
            $surplusEndDate = $surplusPeriod->getEndDate();
            $periodEndDate = $period->getEndDate();
            if($surplusEndDate <= $periodEndDate)
            {
                $totalSurplus += $surplus->getAmount();
            }
        }
        return $totalSurplus;
    }

    public function getPeriodSurplus($startDate, $endDate)
    {
        $periodSurplus = 0.0;
        foreach($this->surpluses as $surplus)
        {
            $surplusDate = $surplus->getDate();
            if($surplusDate >= $startDate && $surplusDate <= $endDate)
            {
                $periodSurplus += $surplus->getAmount();
            }
        }
        return $periodSurplus;
    }
    
        public function getPeriodYield($startDate, $endDate)
        {
            $totalYield = 0.0;
            foreach($this->devices as $device)
            {
                $totalYield += $device->getPeriodYield($startDate, $endDate);
            }
            return $totalYield;
        }

    public function getSurplusByDate($date)
    {
        $date = $date->format('Y-m-d');
        foreach($this->surpluses as $surplus)
        {
            $surplusDate = $surplus->getDate()->format('Y-m-d');
            if($surplusDate === $date)
            {
                return $surplus;
            }
        }
        return null;
    }

    public function createMessage($startDate, $endDate)
    {
        $messageContent = new MessageContent(); 
        $messageContent->setDeviceId($this->id);
        $messageContent->setDeviceStatus($this->status->getName());
        $messageContent->setStartDate($startDate);
        $messageContent->setEndDate($endDate);
        $messageContent->setTotalUsage($this->calculateTotalPeriodUsage($startDate, $endDate));
        foreach($this->devices as $device)
        {
            $messageContent->createMessageDevice($device);
        }
        return json_encode($messageContent, JSON_PRETTY_PRINT);
    }

    private function calculateTotalPeriodUsage($startDate, $endDate)
    {
        $totalSurplus = $this->getPeriodSurplus($startDate, $endDate);        
        $totalYield = $this->getPeriodYield($startDate, $endDate);

        return $totalYield - $totalSurplus;
    }

    public function getId(): ?int
    {
        return $this->id;
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
        return $this->Customer;
    }

    public function setCustomer(Customer $Customer): static
    {
        $this->Customer = $Customer;

        return $this;
    }

    /**
     * @return Collection<int, DeviceSurplus>
     */
    public function getSurpluses(): Collection
    {
        return $this->surpluses;
    }

    public function addSurplus(DeviceSurplus $surplus): static
    {
        if (!$this->surpluses->contains($surplus)) {
            $this->surpluses->add($surplus);
            $surplus->setDeviceManager($this);
        }

        return $this;
    }

    public function removeSurplus(DeviceSurplus $surplus): static
    {
        if ($this->surpluses->removeElement($surplus)) {
            // set the owning side to null (unless already changed)
            if ($surplus->getDeviceManager() === $this) {
                $surplus->setDeviceManager(null);
            }
        }

        return $this;
    }
}
