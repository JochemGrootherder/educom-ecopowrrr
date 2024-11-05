<?php

namespace App\Repository;

use App\Entity\DeviceSurplus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

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
        $device = $params['device'];
        $period = $params['period'];
        $amount = $params['amount'];
        $deviceSurplus = $this->fetchByDeviceAndPeriod($device, $period);
        if(empty($deviceSurplus))
        {
            $deviceSurplus = new DeviceSurplus();
            $deviceSurplus->setDevice($device);
            $deviceSurplus->setPeriod($period);
        }
        dump($deviceSurplus->getAmount());
        dump($amount);
        $deviceSurplus->setAmount($deviceSurplus->getAmount() + $amount);
        dump($deviceSurplus->getAmount());

        $device->addDeviceSurplus($deviceSurplus);

        $this->getEntityManager()->persist($deviceSurplus);
        $this->getEntityManager()->flush();

        return $deviceSurplus;
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
