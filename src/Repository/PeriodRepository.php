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
        $date->settime(0,0);

        // Find the period with a start date less than or equal to the current date
        // and an end date greater than or equal to the current date
        $query = $this->getEntityManager()->createQuery(
            'SELECT p
            FROM App\Entity\Period p
            WHERE :currentDate BETWEEN p.startDate AND p.endDate'
        )->setParameter('currentDate', $date);

        $result = $query->getResult();
        $result = $result[0];

        if(empty($result))
        {
            $period = 
            [
                'startDate' => $date,
                'endDate' => $date
            ];

            $result = $this->savePeriod($period);
        }
        return $result;
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
        $qb = $this->createQueryBuilder('p')
        ->where('p.startDate >= :date')
        ->andWhere('p.endDate <= :date')
        ->setParameter('date', $date);

        $query = $qb->getQuery();

        return $query->getSingleResult();
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
