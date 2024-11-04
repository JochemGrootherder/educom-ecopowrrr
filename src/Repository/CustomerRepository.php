<?php

namespace App\Repository;

use App\Entity\Customer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Validator\Constraints as Assert;

use App\Entity\CustomerAdvisor;

use App\Repository\CustomerAdvisorRepository;

/**
 * @extends ServiceEntityRepository<Customer>
 */
class CustomerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Customer::class);
    }

    public function saveCustomer(ManagerRegistry $doctrine, $params)
    {
        $customerAdvisorRep = $doctrine->getRepository(CustomerAdvisor::class);
        $customerAdvisor = $customerAdvisorRep->fetchCustomerAdvisor(1);
        $customer = new Customer();
        $customer->setZipcode($params['zipcode']);
        $customer->setHousenumber($params['housenumber']);
        $customer->setFirstName($params['firstname']);
        $customer->setLastName($params['lastname']);
        $customer->setGender($params['gender']);
        $customer->setEmail($params['email']);
        $customer->setPhonenumber($params['phonenumber']);
        $date = date_create_from_format("Y-m-d", $params['date_of_birth']);
        $customer->setDateOfBirth($date);
        $customer->setBankDetails($params['bank_details']);
        $customer->setCustomerAdvisor($customerAdvisor);

        //CITY AND ADRESS MUST BE GOTTEN FROM postcode.tech
        $customer->setCity("Test");
        $customer->setAddress("Test");

        $this->getEntityManager()->persist($customer);
        $this->getEntityManager()->flush();

        return $customer;
    }

    //    /**
    //     * @return Customer[] Returns an array of Customer objects
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

    //    public function findOneBySomeField($value): ?Customer
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
