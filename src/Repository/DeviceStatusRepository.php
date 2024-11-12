<?php

namespace App\Repository;

use App\Entity\DeviceStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DeviceStatus>
 */
class DeviceStatusRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DeviceStatus::class);
    }

    public function saveDeviceStatus($params)
    {
        if(!empty($params['id']))
        {
            $deviceStatus = $this->find($params['id']);
        }
        if(empty($deviceStatus))
        {
            $deviceStatus = new DeviceStatus();
        }
        $deviceStatus->setName($params['name']);
        $this->getEntityManager()->persist($deviceStatus);
        $this->getEntityManager()->flush();
        return $deviceStatus;
    }

    public function CreateFromArray($data)
    {
        foreach($data as $values)
        {
            $deviceStatus = 
            [
                "id" => (int)$values["id"],
                "name" => $values["name"]
            ];
            $this->saveDeviceStatus($deviceStatus);
        }
    }

    public function fetchStatus($name)
    {
        $name = strtolower($name);
        $status = $this->findOneBy(['name' => $name]);
        if($status == null)
        {
            $status = new Devicestatus();
            $status->setName($name);
            
            $this->getEntityManager()->persist($status);
            $this->getEntityManager()->flush();
        }
        return $status;
    }

    //    /**
    //     * @return DeviceStatus[] Returns an array of DeviceStatus objects
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

    //    public function findOneBySomeField($value): ?DeviceStatus
    //    {
    //        return $this->createQueryBuilder('d')
    //            ->andWhere('d.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
