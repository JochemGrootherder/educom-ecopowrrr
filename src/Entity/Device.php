<?php

namespace App\Entity;

use App\Repository\DeviceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: DeviceRepository::class)]
class Device
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'devices')]
    #[ORM\JoinColumn(nullable: false)]
    private ?DeviceManager $DeviceManager = null;

    #[ORM\Column(length: 80)]
    private ?string $serialNumber = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?DeviceType $deviceType = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
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

    public function generateRandomYield()
    {
        $value = rand(0, 1000);
        $date = date('Y-m-d');
        $date = date_create_from_format("Y-m-d", $date);
        $date->setTime(0,0);
        
        $params = [
            "yield" => $value,
            "date" => $date->format('Y-m-d'),
        ];
        $this->saveLocalData($params);
    }

    private function saveLocalData($params)
    {
        $text = json_encode($params);
        $file = __DIR__.'/../../public/DeviceData/'. $this->id . '.txt';
        //write data to local file
        $myFile = fopen($file, "a");
        fwrite($myFile, $text);
        fwrite($myFile, "\n");

        fclose($myFile);

    }

    private function getLocalData()
    {
        $file = __DIR__.'/../../public/DeviceData/'. $this->id . '.txt';
        $myFile = fopen($file, "r");
        
        $data = [];
        while(($line = fgets($myFile)) !== false)
        {
            $data[] = json_decode($line, true);
        }
        fclose($myFile);
        return $data;
    }

    public function getLocalPeriodYield($startDate, $endDate)
    {
        $data = $this->getLocalData();
        $startDate->setTime(0,0);
        $endDate->setTime(0,0);
        $totalYield = 0.0;
        foreach($data as $yield)
        {
            $yieldDate = date_create_from_format("Y-m-d", $yield['date']);
            $yieldDate->setTime(0,0);
            if($yieldDate >= $startDate
            && $yieldDate <= $endDate)
            {
                $totalYield += $yield['yield'];
            }
        }
        return $totalYield;

    }

    public function getLocalYieldUntillDate($date)
    {
        $startDate = "1970-01-01";
        $startDate = date_create_from_format("Y-m-d", $startDate);
        return $this->getLocalPeriodYield($startDate, $date);
    }
    
    public function getYieldUntillDate($date)
    {
        $totalYield = 0.0;
        foreach($this->deviceYields as $yield)
        {
            $yieldDate = $yield->getDate();
            if($yieldDate <= $date)
            {
                $totalYield += $yield->getAmount();
            }
        }
        return $totalYield;
    }

    public function getPeriodYield($startDate, $endDate)
    {
        $totalYield = 0.0;
        foreach($this->deviceYields as $yield)
        {
            $yieldDate = $yield->getDate();
            $yieldDate->setTime(0,0);
            $startDate->setTime(0,0);
            $endDate->setTime(0,0);
            if($yieldDate >= $startDate
            && $yieldDate <= $endDate)
            {
                $totalYield += $yield->getAmount();
            }
        }
        return $totalYield;
    }

    public function getYieldByPeriod($period)
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
