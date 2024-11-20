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

use App\Entity\DeviceManager;
use App\Entity\Message;

#[AsCommand(
    name: 'CreateMessage',
    description: 'Add a short description for your command',
)]
class CreateMessageCommand extends Command
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
            ->addArgument('deviceManagerId', InputArgument::REQUIRED, 'Device manager id of which the message will be created')
            ->addArgument('startDate', InputArgument::REQUIRED, 'Start date of the period of interest')
            ->addArgument('endDate', InputArgument::REQUIRED, 'Start date of the period of interest')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $deviceManagerId = $input->getArgument('deviceManagerId');
        $startDate = $input->getArgument('startDate');
        $endDate = $input->getArgument('endDate');

        $data = 
        [
            'deviceManagerId' => $deviceManagerId,
            'startDate' => $startDate,
            'endDate' => $endDate
        ];
        $data = json_encode($data);
        
        $url = "http://localhost:8000/DeviceManager/createMessage";
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Accept: application/json', 'Content-Type: application/json'));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

        $response = curl_exec($curl);
        curl_close($curl);
        if($response)
        {
            $handleResponse = $this->handleMessageResponse($response);
            $io->success($handleResponse);
            return Command::SUCCESS;
        }
        return Command::FAILURE;

        $deviceManagerRep = $this->doctrine->getRepository(DeviceManager::class);
        $deviceManager = $deviceManagerRep->fetch($deviceManagerId);
        if($deviceManager)
        {
            $deviceManager->generateRandomUsage();
            $startDate = date_create_from_format("Y-m-d", $startDate);
            $endDate = date_create_from_format("Y-m-d", $endDate);
            $message = $deviceManager->createMessage($startDate, $endDate);
            $response = $this->sendMessage($message);

            $io->success('Message created successfully');
            $io->info($response);

            return Command::SUCCESS;
        }
        
        $io->error('Failed to create message for device id: '. $deviceManagerId . '. Device does not exist');
        return Command::FAILURE;
    }

    private function sendMessage($message)
    {
        $url = "http://localhost:8000/DeviceManager/send";
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Accept: application/json', 'Content-Type: application/json'));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $message);

        $response = curl_exec($curl);
        curl_close($curl);

        return $response;
    }

    

    public function handleMessageResponse($response)
    {
        $data = json_decode($response, true);

        $deviceManagerRep = $this->doctrine->getRepository(DeviceManager::class);
        $deviceManagerRep->storeMessageData($data);

        $response = json_encode($data);
        return $response;
    }
}
