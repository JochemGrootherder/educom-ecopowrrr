<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\DeviceManagerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Mapping\ClassMetadata;

use App\Message\MessageContent;

#[ORM\Entity(repositoryClass: DeviceManagerRepository::class)]
#[ApiResource]
class DeviceManager
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Assert\Uuid]
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
    
    public function generateRandomUsage()
    {
        foreach($this->getDevices() as $device)
        {
            if($device->getDeviceStatus()->getName() == "active")
            {
                $device->generateRandomYield();
            }
        }

        $date = date('Y-m-d');
        $date = date_create_from_format("Y-m-d", $date);
        $date->settime(0,0);
        
        $periodYield = $this->getLocalPeriodYield($date, $date);
        $value = rand(0, ($periodYield * 1.1));
        
        $params = [
            "surplus" => $value,
            "date" => $date->format('Y-m-d'),
        ];
        $this->saveLocalData($params);
    }

    private function saveLocalData($params)
    {
        $text = json_encode($params);
        $file = __DIR__.'/../../public/DeviceManagerData/'. $this->id . '.txt';
        //write data to local file
        $myFile = fopen($file, "a");
        fwrite($myFile, $text);
        fwrite($myFile, "\n");

        fclose($myFile);

    }

    private function getLocalData()
    {
        $file = __DIR__.'/../../public/DeviceManagerData/'. $this->id . '.txt';
        $myFile = fopen($file, "r");
        
        $data = [];
        while(($line = fgets($myFile)) !== false)
        {
            $data[] = json_decode($line, true);
        }
        fclose($myFile);
        return $data;
    }

    private function getLocalPeriodSurplus($startDate, $endDate)
    {
        $data = $this->getLocalData();
        $totalSurplus = 0.0;
        $startDate->setTime(0,0);
        $endDate->setTime(0,0);
        foreach($data as $surplus)
        {
            $surplusDate = date_create_from_format("Y-m-d", $surplus['date']);
            $surplusDate->setTime(0,0);
            if($surplusDate >= $startDate
            && $surplusDate <= $endDate)
            {
                $totalSurplus += $surplus['surplus'];
            }
        }
        return $totalSurplus;

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
        $startDate->setTime(0,0);
        $endDate->setTime(0,0);
        $periodSurplus = 0.0;
        foreach($this->surpluses as $surplus)
        {
            $surplusDate = $surplus->getDate();
            $surplusDate->setTime(0,0);
            if($surplusDate >= $startDate
            && $surplusDate <= $endDate 
            && $surplus->getPeriod() == null)
            {
                $periodSurplus += $surplus->getAmount();
            }
        }
        return $periodSurplus;
    }
    
        public function getLocalPeriodYield($startDate, $endDate)
        {
            $totalYield = 0.0;
            foreach($this->devices as $device)
            {
                if($device->getDeviceStatus()->getName() == "active")
                {
                    $totalYield += $device->getLocalPeriodYield($startDate, $endDate);
                }
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

        public function getSurplusByPeriod($period)
        {
            foreach($this->surpluses as $surplus)
            {
                if($surplus->getPeriod() === $period)
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
        $date = date('Y-m-d');
        $date = date_create_from_format("Y-m-d", $date);
        $date->setTime(0,0);
        $messageContent->setDate($date);
        $messageContent->setStartDate($startDate);
        $messageContent->setEndDate($endDate);
        $messageContent->setTotalUsage($this->calculateTotalPeriodUsage($startDate, $endDate));
        foreach($this->devices as $device)
        {
            if($device->getDeviceStatus()->getName() == "active")
            {
                $messageContent->createMessageDevice($device);
            }
        }
        return json_encode($messageContent, JSON_PRETTY_PRINT);
    }

    private function calculateTotalPeriodUsage($startDate, $endDate)
    {
        $totalSurplus = $this->getLocalPeriodSurplus($startDate, $endDate);        
        $totalYield = $this->getLocalPeriodYield($startDate, $endDate);

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
