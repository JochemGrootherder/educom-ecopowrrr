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

    public function fetch($id)
    {
        return $this->find($id);
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
        
        $addressInfo = $this->GetAddressInfo($params['zipcode'], $params['housenumber']);
        $customer->setCity("temp");
        $customer->setAddress("temp");
        if(!empty($addressInfo['city']))
        {
            $customer->setCity($addressInfo['city']);
            $customer->setAddress($addressInfo['street']);
        }
        
        $this->getEntityManager()->persist($customer);
        $this->getEntityManager()->flush();

        return $customer;
    }

    private function GetAddressInfo(string $zipcode, string $housenumber)
    {
        $url = "https://postcode.tech/api/v1/postcode/full?postcode={$zipcode}&number={$housenumber}";
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        
        $headers = array('Authorization: Bearer a055aa49-5445-4395-aded-18aa46d48e08');

        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($curl);
        curl_close($curl);

        $data = json_decode($response, true);
        return $data;
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
