<?php

namespace App\Repository;

use App\Entity\DeviceType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DeviceType>
 */
class DeviceTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DeviceType::class);
    }

    public function saveDeviceType($params)
    {
        if(!empty($params['id']))
        {
            $deviceType = $this->find($params['id']);
        }
        if(empty($deviceType))
        {
            $deviceType = new DeviceType();
        }
        $deviceType->setName($params['name']);
        $this->getEntityManager()->persist($deviceType);
        $this->getEntityManager()->flush();
        return $deviceType;
    }

    public function CreateFromArray($data)
    {
        foreach($data as $values)
        {
            $deviceType = 
            [
                "id" => (int)$values["id"],
                "name" => $values["name"]
            ];
            $this->saveDeviceType($deviceType);
        }
    }

    public function fetchType($name)
    {
        $name = strtolower($name);
        $type = $this->findOneBy(['name' => $name]);
        if($type == null)
        {
            $type = new DeviceType();
            $type->setName($name);
            
            $this->getEntityManager()->persist($type);
            $this->getEntityManager()->flush();
        }
        return $type;
    }

    //    /**
    //     * @return DeviceType[] Returns an array of DeviceType objects
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

    //    public function findOneBySomeField($value): ?DeviceType
    //    {
    //        return $this->createQueryBuilder('d')
    //            ->andWhere('d.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
