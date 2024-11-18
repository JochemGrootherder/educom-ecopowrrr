<?php

namespace App\Controller;

require __DIR__.'/../../vendor/autoload.php';
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\Persistence\ManagerRegistry;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

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

    #[Route('/create', name: 'create')]
    public function CreateNewCustomer(Request $request, ManagerRegistry $doctrine): Response
    {
        $params = json_decode($request->getContent(), true);
       
        $customer = 
        [
            "customer_advisor_id" => $params['customer_advisor_id'],
            "zipcode" => $params["zipcode"],
            "housenumber" => $params['housenumber'],
            "firstname" => $params['firstname'],
            "lastname" => $params['lastname'],
            "gender" => $params['gender'],
            "email" => $params['email'],
            "phonenumber" => $params['phonenumber'],
            "date_of_birth" => $params['date_of_birth'],
            "bank_details" => $params['bank_details']
        ];

        $customerRep = $doctrine->getRepository(Customer::class);
        $customerResult = $customerRep->saveCustomer($customer);

        $deviceManager =
        [
            "customer" => $customerResult,
            "status" => "active"
        ];

        $deviceManagerRep = $doctrine->getRepository(DeviceManager::class);
        $deviceManagerResult = $deviceManagerRep->saveDeviceManager($deviceManager);
        $response = new Response(json_encode($params));
        return $response;
    }

    /*#[Route('/create', name: 'createCustomer')]
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
        $customerResult = $customerRep->saveCustomer($customer);
        dump($customerResult);

        $deviceManager =
        [
            "customer_id" => $customerResult->getId(),
            "status_id" => 1
        ];

        $deviceManagerRep = $doctrine->getRepository(DeviceManager::class);
        $deviceManagerResult = $deviceManagerRep->saveDeviceManager($deviceManager);

        dump($deviceManagerResult);

        return $this->render('customer/index.html.twig', [
            'controller_name' => 'CustomerController',
        ]);
    }*/
}
