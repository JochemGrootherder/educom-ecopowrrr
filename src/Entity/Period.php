<?php

namespace App\Entity;

use App\Repository\PeriodRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PeriodRepository::class)]
class Period
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $startDate = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $endDate = null;

    /**
     * @var Collection<int, DeviceYield>
     */
    #[ORM\OneToMany(targetEntity: DeviceYield::class, mappedBy: 'Period')]
    private Collection $deviceYields;

    /**
     * @var Collection<int, DeviceSurplus>
     */
    #[ORM\OneToMany(targetEntity: DeviceSurplus::class, mappedBy: 'Period')]
    private Collection $deviceSurpluses;

    public function __construct()
    {
        $this->deviceYields = new ArrayCollection();
        $this->deviceSurpluses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): static
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeInterface $endDate): static
    {
        $this->endDate = $endDate;

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
            $deviceYield->setPeriod($this);
        }

        return $this;
    }

    public function removeDeviceYield(DeviceYield $deviceYield): static
    {
        if ($this->deviceYields->removeElement($deviceYield)) {
            // set the owning side to null (unless already changed)
            if ($deviceYield->getPeriod() === $this) {
                $deviceYield->setPeriod(null);
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
            $deviceSurplus->setPeriod($this);
        }

        return $this;
    }

    public function removeDeviceSurplus(DeviceSurplus $deviceSurplus): static
    {
        if ($this->deviceSurpluses->removeElement($deviceSurplus)) {
            // set the owning side to null (unless already changed)
            if ($deviceSurplus->getPeriod() === $this) {
                $deviceSurplus->setPeriod(null);
            }
        }

        return $this;
    }
}
