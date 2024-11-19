<?php

namespace App\Repository;

use App\Entity\DeviceYield;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

use App\Entity\Device;
use App\Entity\Period;

/**
 * @extends ServiceEntityRepository<DeviceYield>
 */
class DeviceYieldRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DeviceYield::class);
    }

    public function saveDeviceYield($params)
    {
        if(!empty($params['device_id']))
        {
            $deviceRep = $this->getEntityManager()->getRepository(Device::class);
            $device = $deviceRep->fetch($params['device_id']);
        }
        else
        {
            $device = $params['device'];
        }
        if(!empty($params['date']))
        {
            $date = $params['date'];
            $date = date_create_from_format("Y-m-d", $date);
        }
        else
        {
            $currentDate = date('Y-m-d');
            $date = date_create_from_format("Y-m-d", $currentDate);
        }
        
        $date->settime(0,0);
        $amount = $params['amount'];
        //$deviceYield = $this->fetchByDeviceAndPeriod($device, $period);
        $deviceYield = $device->getYieldByDate($date);
        
        if(empty($deviceYield))
        {
            $deviceYield = new DeviceYield();
            $deviceYield->setDevice($device);
            $deviceYield->setDate($date);
        }
        $deviceYield->setAmount($amount);

        $device->addDeviceYield($deviceYield);

        $this->getEntityManager()->persist($deviceYield);
        $this->getEntityManager()->flush();

        return $deviceYield;
    }
    public function CreateFromArray($data)
    {
        foreach($data as $values)
        {
            $yield = 
            [
                "id" => (int)$values["id"],
                "device_id" => $values["device_id"],
                "date" => $values["date"],
                "amount" => $values["amount"]
            ];
            $this->saveDeviceYield($yield);
        }
    }

    public function fetchByDeviceAndPeriod($device, $period)
    {
        $result = $this->findOneBy(["Device" => $device, "Period" => $period]);
        return $result;
    }

    //    /**
    //     * @return DeviceYield[] Returns an array of DeviceYield objects
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

    //    public function findOneBySomeField($value): ?DeviceYield
    //    {
    //        return $this->createQueryBuilder('d')
    //            ->andWhere('d.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
