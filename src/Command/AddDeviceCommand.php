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

use App\Entity\Device;

#[AsCommand(
    name: 'AddDevice',
    description: 'Add a short description for your command',
)]
class AddDeviceCommand extends Command
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
            ->addArgument('deviceManagerId', InputArgument::REQUIRED, 'Device manager id to which the device will be added')
            ->addArgument('serialNumber', InputArgument::REQUIRED, 'serial number of the device')
            ->addArgument('deviceType', InputArgument::REQUIRED, 'Type of device to be added')
            ->addOption('deviceStatus', 's',  InputOption::VALUE_OPTIONAL, "Status of the device. active or inactive, default active")
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $serialNumber = $input->getArgument('serialNumber');
        $deviceManagerId = $input->getArgument('deviceManagerId');
        $deviceType = $input->getArgument('deviceType');
        $deviceStatus = $input->getOption('deviceStatus');
        if(!$deviceStatus)
        {
            $deviceStatus = "active";
        }
        $device = 
        [
            "serial_number" => $serialNumber,
            "device_status" => $deviceStatus,
            "device_type" => $deviceType,
            "device_manager_id" => $deviceManagerId
        ];
        $deviceRep = $this->doctrine->getRepository(Device::class);
        $result = $deviceRep->saveDevice($device);
        if(!$result)
        {
            $io->error("Failed to add device");
            return Command::FAILURE;
        }
        $io->success("Device added successfully");
        return Command::SUCCESS;
    }
}
