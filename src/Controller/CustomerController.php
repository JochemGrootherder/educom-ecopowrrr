<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\Persistence\ManagerRegistry;

use App\Entity\Customer;
use App\Entity\DeviceManager;

use App\Repository\DeviceManagerRepository;

#[Route('/customer')]
class CustomerController extends AbstractController
{
    #[Route('/', name: 'customerpage')]
    public function index(): Response
    {
        return $this->render('customer/index.html.twig', [
            'controller_name' => 'CustomerController',
        ]);
    }

    #[Route('/create', name: 'createCustomer')]
    public function CreateCustomer(ManagerRegistry $doctrine)
    {
        $customer = 
        [
            "zipcode" => "",
            "housenumber" => 17,
            "firstname" => "John",
            "lastname" => "Doe",
            "gender" => "male",
            "email" => "john.doe@example.com",
            "phonenumber" => "0612345678",
            "date_of_birth" => "1970-01-01",
            "bank_details" => "NL01ABNA0123456789"
        ];

        $customerRep = $doctrine->getRepository(Customer::class);
        $customerResult = $customerRep->saveCustomer($doctrine, $customer);
        dump($customerResult);

        $deviceManager =
        [
            "customer_id" => $customerResult->getId(),
            "status_id" => 1
        ];

        $deviceManagerRep = $doctrine->getRepository(DeviceManager::class);
        $deviceManagerResult = $deviceManagerRep->saveDeviceManager($doctrine, $deviceManager);

        dump($deviceManagerResult);

        return $this->render('customer/index.html.twig', [
            'controller_name' => 'CustomerController',
        ]);
    }
}
