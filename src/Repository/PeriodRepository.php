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
        // Create a new period entity
        $period = new Period();
        $period->setStartDate($params['start_date']);
        $period->setEndDate($params['end_date']);

        // Save the period entity
        $this->getEntityManager()->persist($period);
        $this->getEntityManager()->flush();
        return $period;
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
            WHERE :currentDate BETWEEN p.start_date AND p.end_date'
        )->setParameter('currentDate', $date);

        $result = $query->getResult();
        $result = $result[0];

        if(empty($result))
        {
            $period = 
            [
                'start_date' => $date,
                'end_date' => $date
            ];

            $result = $this->savePeriod($period);
        }
        return $result;
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
