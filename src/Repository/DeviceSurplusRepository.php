<?php

namespace App\Repository;

use App\Entity\DeviceSurplus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

use App\Entity\Device;
use App\Entity\Period;

/**
 * @extends ServiceEntityRepository<DeviceSurplus>
 */
class DeviceSurplusRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DeviceSurplus::class);
    }

    public function saveDeviceSurplus($params)
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
        if(!empty($params['period_id']))
        {
            $periodRep = $this->getEntityManager()->getRepository(Period::class);
            $period = $periodRep->fetch($params['period_id']);
        }
        else
        {
            $period = $params['period'];
        }

        $amount = $params['amount'];
        $deviceSurplus = $this->fetchByDeviceAndPeriod($device, $period);
        if(empty($deviceSurplus))
        {
            $deviceSurplus = new DeviceSurplus();
            $deviceSurplus->setDevice($device);
            $deviceSurplus->setPeriod($period);
        }
        $deviceSurplus->setAmount($deviceSurplus->getAmount() + $amount);

        $device->addDeviceSurplus($deviceSurplus);

        $this->getEntityManager()->persist($deviceSurplus);
        $this->getEntityManager()->flush();

        return $deviceSurplus;
    }
    
    public function CreateFromArray($data)
    {
        foreach($data as $values)
        {
            $surplus = 
            [
                "id" => (int)$values["id"],
                "device_id" => $values["device_id"],
                "period_id" => $values["period_id"],
                "amount" => $values["amount"]
            ];
            $this->saveDeviceSurplus($surplus);
        }
    }

    public function fetchByDeviceAndPeriod($device, $period)
    {
        $result = $this->findOneBy(["Device" => $device, "Period" => $period]);
        return $result;
    }
    //    /**
    //     * @return DeviceSurplus[] Returns an array of DeviceSurplus objects
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

    //    public function findOneBySomeField($value): ?DeviceSurplus
    //    {
    //        return $this->createQueryBuilder('d')
    //            ->andWhere('d.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
