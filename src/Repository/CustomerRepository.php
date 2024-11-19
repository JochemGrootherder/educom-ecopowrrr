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

    public function fetchAll()
    {
        return $this->findAll();
    }

    public function saveCustomer($params)
    {
        if(!empty($params['id']))
        {
            $customer = $this->fetch($params['id']);
        }
        if(empty($customer))
        {
            $customer = new Customer();
        }
        $customerAdvisorRep = $this->getEntityManager()->getRepository(CustomerAdvisor::class);
        $customerAdvisor = $customerAdvisorRep->fetchCustomerAdvisor($params['customer_advisor_id']);
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
            $customer->setMunicipality($addressInfo['municipality']);
            $customer->setProvince($addressInfo['province']);
            $customer->setLatitude($addressInfo['geo']['lat']);
            $customer->setLongitude($addressInfo['geo']['lon']);
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
        $postcode_tech_string = 'Authorization: Bearer '. $_ENV['POSTCODE_TECH_KEY'];
        $headers = array($postcode_tech_string);

        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($curl);
        curl_close($curl);

        $data = json_decode($response, true);
        return $data;
    }

    public function CreateFromArray($data)
    {
        foreach($data as $values)
        {
            $customer = 
            [
                "id" => (int)$values["id"],
                "customer_advisor_id" => (int)$values["customer_advisor_id"],
                "zipcode" => $values["zipcode"],
                "housenumber" => (int)$values["housenumber"],
                "firstname" => $values["firstname"],
                "lastname" => $values["lastname"],
                "gender" => $values["gender"],
                "email" => $values["email"],
                "phonenumber" => $values["phonenumber"],
                "date_of_birth" => $values["date_of_birth"],
                "bank_details" => $values["bankdetails"]
            ];
            $this->SaveCustomer($customer);
        }
    }

    public function findByZipcode(string $zipcode)
    {
        if(preg_match('/^[0-9]{4}$/', $zipcode))
        {
            $zipcode = '%'.$zipcode.'%';
            $query = $this->getEntityManager()->createQuery(
                'SELECT c
                FROM App\Entity\customer c
                WHERE c.zipcode LIKE :zipcode'
            )->setParameter('zipcode', $zipcode);
            
            return $query->getResult();
        }
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
