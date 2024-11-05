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
