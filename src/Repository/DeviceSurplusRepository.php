<?php

namespace App\Repository;

use App\Entity\DeviceSurplus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

use App\Entity\DeviceManager;
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
        $periodRep = $this->getEntityManager()->getRepository(Period::class);
        if(!empty($params['device_manager_id']))
        {
            $deviceManagerRep = $this->getEntityManager()->getRepository(DeviceManager::class);
            $deviceManager = $deviceManagerRep->fetch($params['device_manager_id']);
        }
        else
        {
            $deviceManager = $params['deviceManager'];
        }
        if(!empty($params['date']))
        {
            $date = $params['date'];
        }
        else
        {
            $date = date('Y-m-d');
        }
        $date = date_create_from_format("Y-m-d", $date);
        $date->settime(0,0);

        
        $period = null;
        if(!empty($params['period']))
        {
            $period = $params['period'];
            $deviceSurplus = $deviceManager->getSurplusByPeriod($period);
        }
        else
        {
            $deviceSurplus = $deviceManager->getSurplusByDate($date);
        }
        

        $amount = $params['amount'];
        if(empty($deviceSurplus))
        {
            $deviceSurplus = new DeviceSurplus();
            $deviceSurplus->setDeviceManager($deviceManager);
            $deviceSurplus->setDate($date);
            $deviceSurplus->setPeriod($period);
        }
        $deviceSurplus->setAmount($amount);

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
                "device_manager_id" => $values["device_manager_id"],
                "date" => $values["date"],
                "amount" => $values["amount"]
            ];
            $this->saveDeviceSurplus($surplus);
        }
    }

    /*public function fetchByDeviceAndPeriod($device, $period)
    {
        $result = $this->findOneBy(["Device" => $device, "Period" => $period]);
        return $result;
    }*/
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
