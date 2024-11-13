<?php

namespace App\Repository;

use App\Entity\Message;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

use App\Entity\Period;
use App\Entity\DeviceManager;
/**
 * @extends ServiceEntityRepository<Message>
 */
class MessageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Message::class);
    }

    public function saveMessage($params)
    {
        if(!empty($params["id"]))
        {
            $message = $this->fetch($params["id"]);
        }
        if(empty($message))
        {
            $message = new Message();
        }
        $currentDate = date("y-m-d");
        $date = date_create_from_format("y-m-d", $currentDate);

        $deviceManagerRep = $this->getEntityManager()->getRepository(DeviceManager::class);
        $deviceManager = $deviceManagerRep->fetch($params["device_manager_id"]);
        $message->setDeviceManager($deviceManager);
        $message->setDate($date);
        $message->setMessage($params["message"]);

        $this->getEntityManager()->persist($message);
        $this->getEntityManager()->flush();

        return $message;
    }
    
    public function CreateFromArray($data)
    {
        foreach($data as $values)
        {
            $message = 
            [
                "id" => (int)$values["id"],
                "device_manager_id" => $values["device_manager_id"],
                "date" => $values["date"],
            ];
        }
    }

    public function createMessage($deviceManager)
    {
        $periodRep = $this->getEntityManager()->getRepository(Period::class);
        $currentPeriod = $periodRep->getCurrentPeriod();
        
        $messageString = "";
        $startDate = $currentPeriod->getStartDate()->format('Y-m-d');
        $endDate = $currentPeriod->getEndDate()->format('Y-m-d');
        $messageString.= "\"period_start_date\": \"" . $startDate . "\"\n";
        $messageString.= "\"period_end_date\": \"" . $endDate . "\"\n";
        $messageString.= "\"device_manager_id\": ". $deviceManager->getId() . "\n";
        $messageString .= "\"device_status\": \"". $deviceManager->getStatus()->getName() . "\"\n";
        $devices = $deviceManager->getDevices();
        $messageString .= "\"devices: [\n";
        foreach($devices as $device)
        {
            $deviceString = "{\n";
            $deviceString .= "\"serial_number\": \"". $device->getSerialNumber() . "\"\n";
            $deviceString .= "\"device_type\": \"". $device->getDeviceType()->getName() . "\"\n";
            $deviceString .= "\"device_status\": \"". $device->getDeviceStatus()->getName() . "\"\n";
            $deviceString .= "\"device_total_yield\": ". $device->getYieldUntillPeriod($currentPeriod) . "\n";
            $yield = $device->getPeriodYield($currentPeriod);
            $yieldAmount = ($yield ? $yield->getAmount() : 0.0);
            $deviceString .= "\"device_period_yield\": " . $yieldAmount . "\n";
            $deviceString .= "\"device_total_surplus\": ". $device->getSurplusUntillPeriod($currentPeriod) . "\n";
            $surplus = $device->getPeriodSurplus($currentPeriod);
            $surplusAmount = ($surplus ? $surplus->getAmount() : 0.0);
            $deviceString .= "\"device_period_surplus\": " . $surplusAmount . "\n";
            $deviceString .= "},\n";
            $messageString.= $deviceString;
        }
        $messageString .= "]\n";
        $message =
        [
            "device_manager_id" => $deviceManager->getId(),
            "message" => $messageString
        ];
        return $this->saveMessage($message);
    }

    //    /**
    //     * @return Message[] Returns an array of Message objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder("m")
    //            ->andWhere("m.exampleField = :val")
    //            ->setParameter("val", $value)
    //            ->orderBy("m.id", "ASC")
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Message
    //    {
    //        return $this->createQueryBuilder("m")
    //            ->andWhere("m.exampleField = :val")
    //            ->setParameter("val", $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
