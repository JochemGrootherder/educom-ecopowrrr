<?php

namespace App\Repository;

use App\Entity\Device;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

use App\Entity\DeviceManager;
use App\Entity\DeviceStatus;
use App\Entity\DeviceType;
use App\Entity\DeviceYield;
use App\Entity\DeviceSurplus;

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
        $device = new Device();
        $device->setSerialNumber($params['serialNumber']);

        $statusRep = $this->getEntityManager()->getRepository(DeviceStatus::class);
        $status = $statusRep->fetchStatus($params['status']);
        $device->setDeviceStatus($status);

        $deviceTypeRep = $this->getEntityManager()->getRepository(DeviceType::class);
        $deviceType = $deviceTypeRep->fetchType($params['type']);
        $device->setDeviceType($deviceType);

        $deviceManagerRep = $this->getEntityManager()->getRepository(DeviceManager::class);
        $deviceManager = $deviceManagerRep->fetchCustomerAdvisor($params['deviceManagerId']);

        $device->setDeviceManager($deviceManager);

        $this->getEntityManager()->persist($device);
        $this->getEntityManager()->flush();

        return $device;
    }

    public function fetch($id)
    {
        return $this->find($id);
    }

    public function generateRandomYield($deviceId, $period)
    {
        $device = $this->fetch($deviceId);
        $value = rand(0, 1000);
        $deviceYield = [
            'device' => $device,
            'period' => $period,
            'amount' => $value
        ];

        $deviceYieldRep = $this->getEntityManager()->getRepository(DeviceYield::class);
        $deviceYieldRep->saveDeviceYield($deviceYield);
    }

    public function generateRandomSurplus($deviceId, $period)
    {
        $device = $this->fetch($deviceId);
        $value = rand(-1000, 1000);
        $deviceSurplus = [
            'device' => $device,
            'period' => $period,
            'amount' => $value
        ];
        $deviceSurplusRep = $this->getEntityManager()->getRepository(DeviceSurplus::class);
        $deviceSurplusRep->saveDeviceSurplus($deviceSurplus);
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
