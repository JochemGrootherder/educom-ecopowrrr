<?php

namespace App\Message\Device;

Class MessageDevice
{
    private string $serialNumber;
    private string $deviceType;
    private string $deviceStatus;
    private int $deviceTotalYield;
    private int $devicePeriodYield;

    public function __construct()
    {
        
    }

	/**
	 * Get the value of serialNumber
	 *
	 * @return  mixed
	 */
	public function getSerialNumber()
	{
		return $this->serialNumber;
	}

	/**
	 * Set the value of serialNumber
	 *
	 * @param   mixed  $serialNumber  
	 */
	public function setSerialNumber($serialNumber)
	{
		$this->serialNumber = $serialNumber;
	}

	/**
	 * Get the value of deviceType
	 *
	 * @return  mixed
	 */
	public function getDeviceType()
	{
		return $this->deviceType;
	}

	/**
	 * Set the value of deviceType
	 *
	 * @param   mixed  $deviceType  
	 */
	public function setDeviceType($deviceType)
	{
		$this->deviceType = $deviceType;
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
	 * Get the value of deviceTotalYield
	 *
	 * @return  mixed
	 */
	public function getDeviceTotalYield()
	{
		return $this->deviceTotalYield;
	}

	/**
	 * Set the value of deviceTotalYield
	 *
	 * @param   mixed  $deviceTotalYield  
	 */
	public function setDeviceTotalYield($deviceTotalYield)
	{
		$this->deviceTotalYield = $deviceTotalYield;
	}

	/**
	 * Get the value of devicePeriodYield
	 *
	 * @return  mixed
	 */
	public function getDevicePeriodYield()
	{
		return $this->devicePeriodYield;
	}

	/**
	 * Set the value of devicePeriodYield
	 *
	 * @param   mixed  $devicePeriodYield  
	 */
	public function setDevicePeriodYield($devicePeriodYield)
	{
		$this->devicePeriodYield = $devicePeriodYield;
	}
}