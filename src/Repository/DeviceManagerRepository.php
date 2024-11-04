<?php

namespace App\Repository;

use App\Entity\DeviceManager;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

use App\Repository\DeviceStatusRepository;

use App\Entity\DeviceStatus;
use App\Entity\Customer;


/**
 * @extends ServiceEntityRepository<DeviceManager>
 */
class DeviceManagerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DeviceManager::class);
    }

    public function saveDeviceManager(ManagerRegistry $doctrine, $params)
    {
        $deviceManager = new DeviceManager();

        $customerRep = $doctrine->getRepository(Customer::class);
        $customer = $customerRep->find($params['customer_id']);
        $deviceManager->setCustomer($customer);

        $deviceStatusRep = $doctrine->getRepository(DeviceStatus::class);
        $deviceStatus = $deviceStatusRep->find($params['status_id']);

        $deviceManager->setStatus($deviceStatus);

        $this->getEntityManager()->persist($deviceManager);
        $this->getEntityManager()->flush();

        return $deviceManager;
    }

    public function fetchCustomerAdvisor($id)
    {
        return $this->find($id);
    }

//    /**
//     * @return DeviceManager[] Returns an array of DeviceManager objects
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

//    public function findOneBySomeField($value): ?DeviceManager
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
