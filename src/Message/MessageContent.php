<?php

namespace App\Message;

use App\Entity\Device;
use App\Message\MessageDevice;
use Doctrine\DBAL\Types\Types;

class MessageContent implements \JsonSerializable
{
    private string $messageId;
    private int $deviceId;
    private string $deviceStatus;
    private \DateTimeInterface $startDate;
    private \DateTimeInterface $endDate;
    private int $totalUsage;
    private array $devices;

    public function __construct()
    {
        $this->messageId = "test";
    }

    public function jsonSerialize()
    {
        return [
            'message_id' => $this->messageId,
            'device_id' => $this->deviceId,
            'device_status' => $this->deviceStatus,
            'start_date' => $this->startDate->format('Y-m-d'),
            'end_date' => $this->endDate->format('Y-m-d'),	
            'total_usage' => $this->totalUsage,
            'devices' => $this->devices,
        ];
    }

    public function createMessageDevice(Device $device)
    {
        $messageDevice = new MessageDevice();
        $messageDevice->setSerialNumber($device->getSerialNumber());
        $messageDevice->setDeviceStatus($device->getDeviceStatus()->getName());
        $messageDevice->setDeviceType($device->getDeviceType()->getName());
        $messageDevice->setDeviceTotalYield($device->getYieldUntillDate($this->endDate));
        $messageDevice->setDevicePeriodYield($device->getPeriodYield($this->startDate, $this->endDate));

        $this->devices[] = $messageDevice;
    }

	/**
	 * Get the value of messageId
	 *
	 * @return  mixed
	 */
	public function getMessageId()
	{
		return $this->messageId;
	}

	/**
	 * Set the value of messageId
	 *
	 * @param   mixed  $messageId  
	 */
	public function setMessageId($messageId)
	{
		$this->messageId = $messageId;
	}

	/**
	 * Get the value of deviceId
	 *
	 * @return  mixed
	 */
	public function getDeviceId()
	{
		return $this->deviceId;
	}

	/**
	 * Set the value of deviceId
	 *
	 * @param   mixed  $deviceId  
	 */
	public function setDeviceId($deviceId)
	{
		$this->deviceId = $deviceId;
	}

	/**
	 * Get the value of deviceStatus
	 *
	 * @return  mixed
	 */
	public function getDeviceStatus()
	{
		return $this->deviceStatus;
	}

	/**
	 * Set the value of deviceStatus
	 *
	 * @param   mixed  $deviceStatus  
	 */
	public function setDeviceStatus($deviceStatus)
	{
		$this->deviceStatus = $deviceStatus;
	}

	/**
	 * Get the value of date
	 *
	 * @return  mixed
	 */
	public function getStartDate()
	{
		return $this->startDate;
	}

	/**
	 * Set the value of date
	 *
	 * @param   mixed  $date  
	 */
	public function setStartDate($date)
	{
		$this->startDate = $date;
	}

	/**
	 * Get the value of date
	 *
	 * @return  mixed
	 */
	public function getEndDate()
	{
		return $this->endDate;
	}

	/**
	 * Set the value of date
	 *
	 * @param   mixed  $date  
	 */
	public function setEndDate($date)
	{
		$this->endDate = $date;
	}

	/**
	 * Get the value of totalUsage
	 *
	 * @return  mixed
	 */
	public function getTotalUsage()
	{
		return $this->totalUsage;
	}

	/**
	 * Set the value of totalUsage
	 *
	 * @param   mixed  $totalUsage  
	 */
	public function setTotalUsage($totalUsage)
	{
		$this->totalUsage = $totalUsage;
	}

	/**
	 * Get the value of devices
	 *
	 * @return  mixed
	 */
	public function getDevices()
	{
		return $this->devices;
	}

	/**
	 * Set the value of devices
	 *
	 * @param   mixed  $devices  
	 */
	public function setDevices($devices)
	{
		$this->devices = $devices;
	}

    public function addDevice($device)
    {
        $this->devices[] = $device;
        return $this;
    }
}