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
use App\Entity\Period;

#[AsCommand(
    name: 'GenerateRandomYieldAndSurplus',
    description: 'Add a short description for your command',
)]
class GenerateRandomYieldAndSurplusCommand extends Command
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
            ->addArgument('deviceId', InputArgument::REQUIRED, 'Device id of which random values should be generated')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $deviceId = $input->getArgument('deviceId');

        $deviceRep = $this->doctrine->getRepository(Device::class);
        
        $periodRep = $this->doctrine->getRepository(Period::class);
        $period = $periodRep->getCurrentPeriod();
        $result = $deviceRep->generateRandomYieldAndSurplus($deviceId, $period);
        
        if(!$result)
        {
            $io->error('Failed to generate random yield and surplus for device id: '. $deviceId . '. Device does not exist');
            return Command::FAILURE;
        }
        $io->success('Succesfully generated random yield and surplus for device id: '. $deviceId);
        return Command::SUCCESS;
    }
}
