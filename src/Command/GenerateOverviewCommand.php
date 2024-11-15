<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Doctrine\Persistence\ManagerRegistry;

use App\Entity\Customer;
use App\Entity\Price;
use App\Entity\Period;

#[AsCommand(
    name: 'GenerateOverview',
    description: 'Add a short description for your command',
)]
class GenerateOverviewCommand extends Command
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
        ->addOption('Municipality', 'm',  InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY, "Which zipcodes should be shown")
        ->addOption('YearAndPrediction', null,  InputOption::VALUE_NONE, "Get the current year and prediction")
        ->addOption('Year', 'y',  InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY, "Which year should be shown")
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $municipalityRequest = $input->getOption('Municipality');
        $yearAndPredictionRequest = $input->getOption('YearAndPrediction');
        $yearRequest = $input->getOption('Year');
        
        $this->handleMunicipalityRequest($io, $municipalityRequest);
        $this->handleYearAndPredictionRequest($io, $yearAndPredictionRequest);
        $this->handleYearRequest($io, $yearRequest);

        return Command::SUCCESS;
    }

    

    private function handleMunicipalityRequest($io, $municipalityRequest)
    {
        if ($municipalityRequest) {
            $io->note(sprintf('Municipality(s): %s', implode(', ', $municipalityRequest)));
            foreach($municipalityRequest as $zipcode)
            {
                if(!preg_match('/^[0-9]{4}$/', $zipcode))
                {
                    $io->error(sprintf('Invalid zipcode %s. Expected format [1234]', $zipcode));
                    return Command::FAILURE;
                }

                $data = $this->retreiveZipcodeData($zipcode);
                dump($data);
            }
        }
    }

    private function handleYearAndPredictionRequest($io, $yearAndPredictionRequest)
    {
        if ($yearAndPredictionRequest) 
        {
            $io->note('Year and prediction overview requested');
            $data = $this->retreiveYearAndPredictionData();
            dump($data);
        }

    }

    private function handleYearRequest($io, $yearRequest)
    {
        if($yearRequest)
        {
            $io->note(sprintf('Year(s): %s', implode(', ', $yearRequest)));
            foreach($yearRequest as $year)
            {
                if(!preg_match('/^[0-9]{4}$/', $year))
                {
                    $io->error(sprintf('Invalid year %s. Expected format [1234]', $year));
                    return Command::FAILURE;
                }

                $data = $this->retreiveYearData($year);
                dump($data);
            }
        }

    }

    private function retreiveZipcodeData($zipcode)
    {
        //get customers
        $customerRep = $this->doctrine->getRepository(Customer::class);
        $priceRep = $this->doctrine->getRepository(Price::class);
        $customers = $customerRep->findByZipcode($zipcode);
        $data = ["totalYield" => 0.0, "totalSurplus" => 0.0, "revenue" => 0.0];
        foreach($customers as $customer)
        {
            $prices = $priceRep->getPricesByCustomerDesc($customer);
            $deviceManager = $customer->getDeviceManager();
            $devices = $deviceManager->getDevices();

            $totalYield = 0.0;
            foreach($devices as $device)
            {
                foreach($device->getDeviceYields() as $yield)
                {
                    $totalYield += $yield->getAmount();
                }
            }
            
            $totalSurplus = 0.0;
            $surpluses = $deviceManager->getSurpluses();
            foreach($surpluses as $surplus)
            {
                $totalSurplus += $surplus->getAmount();
            }
            $revenue = $this->calculateRevenue($surpluses, $prices);
            
            $data["totalYield"] += $totalYield;
            $data["totalSurplus"] += $totalSurplus;
            $data["revenue"] += $revenue;
        }
        return $data;
    }

    private function retreiveYearAndPredictionData()
    {
        $customerRep = $this->doctrine->getRepository(Customer::class);
        $priceRep = $this->doctrine->getRepository(Price::class);
        $periodRep = $this->doctrine->getRepository(Period::class);

        $periods = $periodRep->getCurrentYearPeriods();

        $revenues = [];

        $customers = $customerRep->fetchAll();
        foreach($customers as $customer)
        {
            $prices = $priceRep->getPricesByCustomerDesc($customer);
            $deviceManager = $customer->getDeviceManager();
            $devices = $deviceManager->getDevices();

            $surplusesToCalculate = [];
            $surpluses = $deviceManager->getSurpluses();
            foreach($surpluses as $surplus)
            {
                foreach($periods as $period)
                {
                    $periodStartDate = $period->getStartDate();
                    $periodEndDate = $period->getEndDate();
                    $surplusDate = $surplus->getDate();

                    if($surplusDate >= $periodStartDate
                    && $surplusDate <= $periodEndDate)
                    {
                        $surplusesToCalculate[] = $surplus;
                        break;
                    }
                }
            }
            $this->calculateRevenuePerPeriod($revenues, $surplusesToCalculate, $prices);
        }
        sort($revenues);
        return $revenues;
    }

    private function retreiveYearData($year)
    {
        $customerRep = $this->doctrine->getRepository(Customer::class);
        $priceRep = $this->doctrine->getRepository(Price::class);
        $periodRep = $this->doctrine->getRepository(Period::class);

        $periods = $periodRep->getYearPeriods($year);

        $revenues = [];
        $a = 0.0;
        $customers = $customerRep->fetchAll();
        foreach($customers as $customer)
        {
            $prices = $priceRep->getPricesByCustomerDesc($customer);
            $deviceManager = $customer->getDeviceManager();
            
            $surplusesToCalculate = [];
            $totalRevenues = [];
            $totalSurplus = 0.0;
            $surpluses = $deviceManager->getSurpluses();
            foreach($surpluses as $surplus)
            {
                foreach($periods as $period)
                {
                    $periodStartDate = $period->getStartDate();
                    $periodEndDate = $period->getEndDate();
                    $surplusDate = $surplus->getDate();
                    if($surplusDate >= $periodStartDate
                    && $surplusDate <= $periodEndDate)
                    {
                        $surplusesToCalculate[] = $surplus;
                        $totalSurplus += $surplus->getAmount();
                        break;
                    }
                }
            }
            $a += $totalSurplus;
            $customerRevenues = [
                "customer" => $customer,
                "surplus" => $totalSurplus, 
                "revenue" => $this->calculateRevenue($surplusesToCalculate, $prices)];
            $revenues[] = $customerRevenues;
        }
        sort($revenues);
        return $revenues;
    }

    private function calculateRevenuePerPeriod(&$revenues, $surpluses, $prices)
    {
        $periodRep = $this->doctrine->getRepository(Period::class);
        foreach($surpluses as $surplus)
        {
            $surplusArr[] = $surplus;
            $surplusDate = $surplus->getDate();
            $addedToRevenue = false;
            foreach($revenues as &$revenue)
            {
                $period = $periodRep->fetch($revenue['period_id']);
                if($period)
                {
                    $periodStartDate= $period->getStartDate();
                    $periodEndDate= $period->getEndDate();
                    if($surplusDate >= $periodStartDate
                    && $surplusDate <= $periodEndDate)
                    {
                        $revenue['surplus'] = $revenue['surplus'] + $surplus->getAmount();
                        $revenue['revenue'] = $revenue['revenue'] + $this->calculateRevenue($surplusArr, $prices);
                        $addedToRevenue = true;
                    }
                }
            }
            //There is no revenue with this period yet
            if(!$addedToRevenue)
            {
                $period = $periodRep->getDatePeriod($surplusDate);
                $periodId = $period->getId();
                $revenues[] = ['period_id' => $periodId,
                    "surplus" => $surplus->getAmount(),
                    "revenue" => $this->calculateRevenue($surplusArr, $prices)
                ];
                
            }
        }
        return $revenues;
    }

    private function calculateRevenue($surpluses, $prices)
    {
        foreach($surpluses as $surplus)
        {
            $amount = $surplus->getAmount();
            $surplusDate = $surplus->getDate();
            
            $revenueAdded = false;
            $revenue = 0.0;
            foreach($prices as $price)
            {
                //if price date is within period
                if($price->getDate() <= $surplusDate
                && !$revenueAdded)
                {
                    $revenue += $amount * $price->getPrice();
                    $revenueAdded = true;
                }
            }
            if(!$revenueAdded)
            {
                $revenue += $amount * $prices[0]->getPrice();
            }

        }
        return $revenue;
    }
}
