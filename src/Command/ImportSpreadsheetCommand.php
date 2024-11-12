<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Doctrine\Persistence\ManagerRegistry;

use App\Entity\CustomerAdvisor;
use App\Entity\Customer;
use App\Entity\Period;
use App\Entity\DeviceStatus;
use App\Entity\DeviceManager;
use App\Entity\DeviceType;
use App\Entity\Device;
use App\Entity\Price;
use App\Entity\Message;
use App\Entity\DeviceYield;
use App\Entity\DeviceSurplus;


#[AsCommand(
    name: 'ImportSpreadsheetCommand',
    description: 'Add a short description for your command',
)]
class ImportSpreadsheetCommand extends Command
{
    private $doctrine;
    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('file', InputArgument::REQUIRED, 'Spreadsheet')
            ->setHelp('This command allows you to import a spreadsheet')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $inputFileName = $input->getArgument('file');
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($inputFileName);
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($inputFileName);
        $sheets = $spreadsheet->getAllSheets();
        $sheetNames = $spreadsheet->getSheetNames();
        
        foreach($sheets as $sheet)
        {
            $data = $this->convertSheetToArray($sheet);
            $title = $sheet->getTitle();
            dump($title);
            $this->CreateFromSheet($title, $data);
        }
        // dump($sheets);


        return Command::SUCCESS;
    }

    private function convertSheetToArray($sheet)
    {
        $data = $sheet->toArray("", false, false);
        $keys = [];
        foreach($data[0] as $key)
        {
            $keys[] = $key;
        }

        $newData = [];
        for($i = 1; $i < count($data); $i++)
        {
            for($j = 0; $j < count($data[$i]); $j++)
            {
                $newData[$i][$keys[$j]] = $data[$i][$j];
            }
        }
        return $newData;
    }

    private function CreateFromSheet($title, $data)
    {
        switch($title)
        {
            case "period":
                $this->createPeriods($data);
                break;
            case "customer_advisor":
                $this->createCustomerAdvisors($data);
                break;
            case "customer":
                $this->createCustomers($data);
                break;
            case "device_status":
                $this->createDeviceStatuses($data);
                break;
            case "device_manager":
                $this->createDeviceManagers($data);
                break;
            case "device_type":
                $this->createDeviceTypes($data);
                break;
            case "device":
                $this->createDevices($data);
                break;
            case "price":
                $this->createPrices($data);
                break;
            case "message":
                $this->createMessages($data);
                break;
            case "yield":
                $this->createDeviceYields($data);
                break;
            case "surplus":
                $this->createDeviceSurpluses($data);
                break;                                                                         
        }
    }
    
    private function createPeriods($data)
    {
        $rep = $this->doctrine->getRepository(Period::class);
        $rep->CreateFromArray($data);
    }
    
    private function createCustomerAdvisors($data)
    {
        $rep = $this->doctrine->getRepository(CustomerAdvisor::class);
        $rep->CreateFromArray($data);
    }
    
    private function createCustomers($data)
    {
        $rep = $this->doctrine->getRepository(Customer::class);
        $rep->CreateFromArray($data);
    }
    
    private function createDeviceStatuses($data)
    {
        $rep = $this->doctrine->getRepository(DeviceStatus::class);
        $rep->CreateFromArray($data);
    }
    
    private function createDeviceManagers($data)
    {
        $rep = $this->doctrine->getRepository(DeviceManager::class);
        $rep->CreateFromArray($data);
    }
    
    private function createDeviceTypes($data)
    {
        $rep = $this->doctrine->getRepository(DeviceType::class);
        $rep->CreateFromArray($data);
    }
    
    private function createDevices($data)
    {
        $rep = $this->doctrine->getRepository(Device::class);
        $rep->CreateFromArray($data);
    }
    
    private function createPrices($data)
    {
        $rep = $this->doctrine->getRepository(Price::class);
        $rep->CreateFromArray($data);
    }
    
    private function createMessages($data)
    {
        $rep = $this->doctrine->getRepository(Message::class);
        $rep->CreateFromArray($data);
    }
    
    private function createDeviceYields($data)
    {
        $rep = $this->doctrine->getRepository(DeviceYield::class);
        $rep->CreateFromArray($data);
    }
    
    private function createDeviceSurpluses($data)
    {
        $rep = $this->doctrine->getRepository(DeviceSurplus::class);
        $rep->CreateFromArray($data);
    }

}
