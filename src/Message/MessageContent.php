<?php

namespace App\Message\Content;

class MessageContent
{
    private string $message_id;
    private int $device_id;
    private string $device_status;
    private string $date;
    private int $total_usage;
    private array $devices;

    public function __construct()
    {

    }

	/**
	 * Get the value of message_id
	 *
	 * @return  mixed
	 */
	public function getMessage_id()
	{
		return $this->message_id;
	}

	/**
	 * Set the value of message_id
	 *
	 * @param   mixed  $message_id  
	 */
	public function setMessage_id($message_id)
	{
		$this->message_id = $message_id;
	}

	/**
	 * Get the value of device_id
	 *
	 * @return  mixed
	 */
	public function getDevice_id()
	{
		return $this->device_id;
	}

	/**
	 * Set the value of device_id
	 *
	 * @param   mixed  $device_id  
	 */
	public function setDevice_id($device_id)
	{
		$this->device_id = $device_id;
	}

	/**
	 * Get the value of device_status
	 *
	 * @return  mixed
	 */
	public function getDevice_status()
	{
		return $this->device_status;
	}

	/**
	 * Set the value of device_status
	 *
	 * @param   mixed  $device_status  
	 */
	public function setDevice_status($device_status)
	{
		$this->device_status = $device_status;
	}

	/**
	 * Get the value of date
	 *
	 * @return  mixed
	 */
	public function getDate()
	{
		return $this->date;
	}

	/**
	 * Set the value of date
	 *
	 * @param   mixed  $date  
	 */
	public function setDate($date)
	{
		$this->date = $date;
	}

	/**
	 * Get the value of total_usage
	 *
	 * @return  mixed
	 */
	public function getTotal_usage()
	{
		return $this->total_usage;
	}

	/**
	 * Set the value of total_usage
	 *
	 * @param   mixed  $total_usage  
	 */
	public function setTotal_usage($total_usage)
	{
		$this->total_usage = $total_usage;
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