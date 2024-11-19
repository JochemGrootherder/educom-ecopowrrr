<?php

namespace App\Repository;

use App\Entity\DeviceManager;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

use App\Repository\DeviceStatusRepository;

use App\Entity\DeviceStatus;
use App\Entity\Device;
use App\Entity\DeviceSurplus;
use App\Entity\Customer;
use App\Entity\Period;


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
        if(empty($params['customer_id']))
        {
            $customer = $params['customer'];
        }else
        {
            $customerRep = $this->getEntityManager()->getRepository(Customer::class);
            $customer = $customerRep->find($params['customer_id']);
        }
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

    public function storeMessageData($data)
    {
        $periodRep = $this->getEntityManager()->getRepository(Period::class);
        $date = date_create_from_format("Y-m-d", $data['date']);
        dump($data);
        if($data['device_status'] == 'active')
        {
            $deviceRep = $this->getEntityManager()->getRepository(Device::class);
            $deviceRep->saveDeviceData($data);

            $totalPeriodYield = 0.0;
            foreach($data['devices'] as $device)
            {
                $totalPeriodYield += $device['device_period_yield'];
            }
            $surplusAmount = $totalPeriodYield - $data['total_usage'];

            $surplus = 
            [
                "deviceManager" => $this->fetch($data['device_id']),
                "period" => $periodRep->getDatePeriod($date),
                "amount" => $surplusAmount
            ];
            $deviceSurplusRep = $this->getEntityManager()->getRepository(DeviceSurplus::class);
            $deviceSurplusRep->saveDeviceSurplus($surplus);
            return true;
        }

        dump("device manager is inactive");
        return false;
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
