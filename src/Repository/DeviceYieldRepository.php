<?php

namespace App\Repository;

use App\Entity\DeviceYield;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

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
        $device = $params['device'];
        $period = $params['period'];
        $amount = $params['amount'];
        $deviceYield = $this->fetchByDeviceAndPeriod($device, $period);
        if(empty($deviceYield))
        {
            $deviceYield = new DeviceYield();
            $deviceYield->setDevice($device);
            $deviceYield->setPeriod($period);
        }
        dump($deviceYield->getAmount());
        dump($amount);
        $deviceYield->setAmount($deviceYield->getAmount() + $amount);
        dump($deviceYield->getAmount());

        $device->addDeviceYield($deviceYield);

        $this->getEntityManager()->persist($deviceYield);
        $this->getEntityManager()->flush();

        return $deviceYield;
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
