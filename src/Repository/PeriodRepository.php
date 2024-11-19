<?php

namespace App\Repository;

use App\Entity\Period;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Period>
 */
class PeriodRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Period::class);
    }

    public function savePeriod($params)
    {
        if(!empty($params['id']))
        {
            $period = $this->fetch($params['id']);
        }
        if(empty($period))
        {
            $period = new Period();
        }
        $startDate = date_create_from_format("Y-m-d", $params['startDate']);
        $endDate = date_create_from_format("Y-m-d", $params['endDate']);
        $period->setStartDate($startDate);
        $period->setEndDate($endDate);

        // Save the period entity
        $this->getEntityManager()->persist($period);
        $this->getEntityManager()->flush();
        return $period;
    }

    public function fetch($id)
    {
        return $this->find($id);
    }
    public function CreateFromArray($data)
    {
        foreach($data as $values)
        {
            $period = 
            [
                "id" => (int)$values["id"],
                "startDate" => $values["startDate"],
                "endDate" => $values["endDate"],
            ];
            $this->savePeriod($period);
        }
    }

    public function getCurrentPeriod()
    {
        // Get the current date
        $currentDate = date('y-m-d');
        $date = date_create_from_format("y-m-d", $currentDate);
        return $this->getDatePeriod($date);
    }

    public function getCurrentYearPeriods()
    {
        $currentYear = date('Y');
        return $this->getYearPeriods($currentYear);
    }

    public function getYearPeriods($year)
    {
        $currentYearStartDate = $year . '-01-01';
        $currentYearEndDate = $year . '-31-12';
        $currentYearStartDate = date_create_from_format("Y-m-d", $currentYearStartDate);
        $currentYearEndDate = date_create_from_format("Y-m-d", $currentYearEndDate);

        $qb = $this->createQueryBuilder('p')
        ->where('p.startDate >= :currentYearStartDate')
        ->andWhere('p.endDate <= :currentYearEndDate')
        ->setParameter('currentYearStartDate', $currentYearStartDate)
        ->setParameter('currentYearEndDate', $currentYearEndDate);

        $query = $qb->getQuery();

        return $query->getResult();
    }

    public function getDatePeriod($date)
    {
        $date->setTime(0,0);

        $query = $this->getEntityManager()->createQuery(
            'SELECT p
            FROM App\Entity\Period p
            WHERE :dateRequested BETWEEN p.startDate AND p.endDate'
        )->setParameter('dateRequested', $date);

        $result = $query->getResult();
        if($result)
        {
            return $result[0];
        }
        
        $period = 
        [
            'startDate' => $date,
            'endDate' => $date
        ];

        return $this->savePeriod($period);
        
    }

    
    //    /**
    //     * @return Period[] Returns an array of Period objects
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

    //    public function findOneBySomeField($value): ?Period
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
