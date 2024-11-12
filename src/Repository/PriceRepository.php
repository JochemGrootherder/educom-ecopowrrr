<?php

namespace App\Repository;

use App\Entity\Price;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

use App\Entity\Customer;

/**
 * @extends ServiceEntityRepository<Price>
 */
class PriceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Price::class);
    }

    public function savePrice($params)
    {
        $customerRep = $this->getEntityManager()->getRepository(Customer::class);
        $customer = $customerRep->fetch($params['customer_id']);

        if(empty($params['date']))
        {
            $currentDate = date('y-m-d');
            $date = date_create_from_format("y-m-d", $currentDate);
            $date->settime(0,0);
        }
        else
        {
            $dateText = $params['date'];
            $currentDate = date('y-m-d');
            $date = date_create_from_format("Y-m-d", $dateText);
            $date->settime(0,0);
        }
        
        $priceEntity = $this->GetCurrentPrice($customer, $date);
        if(empty($priceEntity))
        {
            $priceEntity = new Price();
            $priceEntity->setCustomer($customer);
            $priceEntity->setDate($date);
        }
        $priceEntity->setPrice($params['price']);

        $this->getEntityManager()->persist($priceEntity);
        $this->getEntityManager()->flush();

        return $priceEntity;
    }
    public function CreateFromArray($data)
    {
        foreach($data as $values)
        {
            $price = 
            [
                "id" => (int)$values["id"],
                "date" => $values["date"],
                "customer_id" => $values["customer_id"],
                "price" => $values["price"]
            ];
            $this->savePrice($price);
        }
    }

    private function GetCurrentPrice($customer, $date)
    {
        $query = $this->getEntityManager()->createQuery(
            'SELECT p
            FROM App\Entity\price p
            WHERE p.Customer = :customer
            AND p.date = :current_date'
        )->setParameter('current_date', $date)
        ->setParameter('customer', $customer);

        return $query->getOneOrNullResult();
    }

    public function getPricesByCustomerDesc(Customer $customer)
    {
        $query = $this->getEntityManager()->createQuery(
            'SELECT p
            FROM App\Entity\price p
            WHERE p.Customer = :customer
            ORDER BY p.date DESC'
        )->setParameter('customer', $customer);

        return $query->getResult();
    }

    //    /**
    //     * @return Price[] Returns an array of Price objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Price
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
