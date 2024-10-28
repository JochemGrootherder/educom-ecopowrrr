<?php

namespace App\Entity;

use App\Repository\PeriodRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PeriodRepository::class)]
class Period
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $start_date = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $end_date = null;

    /**
     * @var Collection<int, DeviceYield>
     */
    #[ORM\OneToMany(targetEntity: DeviceYield::class, mappedBy: 'period')]
    private Collection $deviceYields;

    /**
     * @var Collection<int, DeviceSurplus>
     */
    #[ORM\OneToMany(targetEntity: DeviceSurplus::class, mappedBy: 'period')]
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
        return $this->start_date;
    }

    public function setStartDate(\DateTimeInterface $start_date): static
    {
        $this->start_date = $start_date;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->end_date;
    }

    public function setEndDate(\DateTimeInterface $end_date): static
    {
        $this->end_date = $end_date;

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
