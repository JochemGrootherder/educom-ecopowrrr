<?php

namespace App\Controller;

require __DIR__.'/../../vendor/autoload.php';
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
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
    }

    #[Route('/readSpreadsheet', name: 'readSpreadsheet')]
    public function readSpreadsheet()
    {
        $inputFileName = __DIR__.'/../../mockData/mockData.xlsx';
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load('./mockData.xlsx');
        //$spreadsheet = $reader->load($inputFileName);
        //$activeSheet = $spreadsheet->setActiveSheetIndexByName('customer');
        dd($spreadsheet);
    }
}
