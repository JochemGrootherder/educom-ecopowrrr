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
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $deviceManagerId = $input->getArgument('deviceManagerId');

        $deviceManagerRep = $this->doctrine->getRepository(DeviceManager::class);
        $deviceManager = $deviceManagerRep->fetch($deviceManagerId);
        $startDate = "2024-11-01";
        $endDate = "2024-11-30";
        $startDate = date_create_from_format("Y-m-d", $startDate);
        $endDate = date_create_from_format("Y-m-d", $endDate);
        $message = $deviceManager->createMessage($startDate, $endDate);
        dump($message);

        /*$messageRep = $this->doctrine->getRepository(Message::class);
        $message = $messageRep->createMessage($deviceManager);*/

        $io->success('Message created successfully');

        return Command::SUCCESS;
    }
}
