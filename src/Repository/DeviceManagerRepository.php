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

    public function saveDeviceManager($params)
    {
        if(!empty($params['id']))
        {
            $deviceManager = $this->fetch($params['id']);
        }
        if(empty($deviceManager))
        {
            $deviceManager = new DeviceManager();
        }
        $customerRep = $this->getEntityManager()->getRepository(Customer::class);
        $customer = $customerRep->find($params['customer_id']);
        $deviceManager->setCustomer($customer);

        $deviceStatusRep = $this->getEntityManager()->getRepository(DeviceStatus::class);
        $deviceStatus = $deviceStatusRep->fetchStatus($params['status']);

        $deviceManager->setStatus($deviceStatus);

        $this->getEntityManager()->persist($deviceManager);
        $this->getEntityManager()->flush();

        return $deviceManager;
    }

    public function fetch($id)
    {
        return $this->find($id);
    }

    public function CreateFromArray($data)
    {
        foreach($data as $values)
        {
            $DeviceManager = 
            [
                "id" => (int)$values["id"],
                "status" => $values["status"],
                "customer_id" => $values["customer_id"]
            ];
            $this->saveDeviceManager($DeviceManager);
        }
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
