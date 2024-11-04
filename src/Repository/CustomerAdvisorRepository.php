<?php

namespace App\Repository;

use App\Entity\CustomerAdvisor;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CustomerAdvisor>
 */
class CustomerAdvisorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CustomerAdvisor::class);
    }

    public function SaveCustomerAdvisor($params)
    {
        $customerAdvisor = new CustomerAdvisor();
        $customerAdvisor->setUsername($params['username']);
        $customerAdvisor->setPassword($params['password']);

        $this->getEntityManager()->persist($customerAdvisor);
        $this->getEntityManager()->flush();

        return $customerAdvisor;
    }

    public function fetchCustomerAdvisor($id)
    {
        return $this->find($id);
    }

    //    /**
    //     * @return CustomerAdvisor[] Returns an array of CustomerAdvisor objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?CustomerAdvisor
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
