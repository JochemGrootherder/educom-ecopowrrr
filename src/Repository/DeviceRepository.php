<?php

namespace App\Repository;

use App\Entity\Device;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

use App\Entity\DeviceManager;
use App\Entity\DeviceStatus;
use App\Entity\DeviceType;
use App\Entity\DeviceYield;

/**
 * @extends ServiceEntityRepository<Device>
 */
class DeviceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Device::class);
    }

    public function saveDevice($params)
    {
        $device = null;
        if(!empty($params['id']))
        {
            $device = $this->fetch($params['id']);
        }
        if(empty($device))
        {
            $device = new Device();
        }
        $device->setSerialNumber($params['serial_number']);

        $statusRep = $this->getEntityManager()->getRepository(DeviceStatus::class);
        $status = $statusRep->fetchStatus($params['device_status']);
        $device->setDeviceStatus($status);

        $deviceTypeRep = $this->getEntityManager()->getRepository(DeviceType::class);
        $deviceType = $deviceTypeRep->fetchType($params['device_type']);
        $device->setDeviceType($deviceType);

        $deviceManagerRep = $this->getEntityManager()->getRepository(DeviceManager::class);
        $deviceManager = $deviceManagerRep->fetch($params['device_manager_id']);
        if(!$deviceManager)
        {
            return false;
        }
        $device->setDeviceManager($deviceManager);

        $this->getEntityManager()->persist($device);
        $this->getEntityManager()->flush();

        return $device;
    }

    public function fetch($id)
    {
        return $this->find($id);
    }
    
    public function CreateFromArray($data)
    {
        foreach($data as $values)
        {
            $Device = 
            [
                "id" => (int)$values["id"],
                "device_status" => $values["device_status"],
                "device_manager_id" => $values["device_manager_id"],
                "device_type" => $values["device_type"],
                "serial_number" => $values["serial_number"]
            ];
            $this->saveDevice($Device);
        }
    }

    public function saveDeviceData($data)
    {
        $deviceManagerId = $data['device_id'];
        $deviceManagerRep = $this->getEntityManager()->getRepository(DeviceManager::class);
        $deviceManager = $deviceManagerRep->fetch($deviceManagerId);
        foreach($data['devices'] as $deviceData)
        {
            $serialNumber = $deviceData['serial_number'];
            $device = $this->findOneBy([
                "serialNumber" => $serialNumber,
                "DeviceManager" => $deviceManager
            ]);
            if(!$device)
            {
                $params = [
                    'device_status' => $deviceData['device_status'],
                    'device_type' => $deviceData['device_type'],
                    'device_manager_id' => $deviceManagerId,
                    'serial_number' => $serialNumber
                ];
                $device = $this->saveDevice($params);
            }
            $deviceYieldRep = $this->getEntityManager()->getRepository(DeviceYield::class);
            $deviceYieldParams = [
                "device" => $device,
                "date" => $data['date'],
                "amount" => $deviceData['device_period_yield']
            ];
            $deviceYieldRep->saveDeviceYield($deviceYieldParams);
        }
    }

    //    /**
    //     * @return Device[] Returns an array of Device objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('d')
    //            ->andWhere('d.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('d.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Device
    //    {
    //        return $this->createQueryBuilder('d')
    //            ->andWhere('d.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
