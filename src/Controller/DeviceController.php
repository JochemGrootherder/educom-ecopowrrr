<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\Persistence\ManagerRegistry;

use App\Entity\Device;
use App\Entity\Period;

#[Route('/device')]
class DeviceController extends AbstractController
{
    #[Route('/', name: 'device')]
    public function index(): Response
    {
        return $this->render('device/index.html.twig', [
            'controller_name' => 'DeviceController',
        ]);
    }

    #[Route('/create/{deviceManagerId}', name: 'createDevice', requirements: ['deviceManagerId' => '\d+'])]
    public function CreateDevice($deviceManagerId, ManagerRegistry $doctrine) : Response
    {
        $device = 
        [
            "serialNumber" => "testDevice123",
            "status" => "active",
            "type" => "solar",
            "deviceManagerId" => $deviceManagerId
        ];
        $deviceRep = $doctrine->getRepository(Device::class);
        $deviceRep->saveDevice($device);
        dd($device);

        return $this->render('device/index.html.twig', [
            'controller_name' => 'DeviceController',
        ]);
    }

    #[Route('/generate/{deviceId}', name: 'generateDeviceYieldAndSurplus', requirements:['deviceId' => '\d+'])]
    public function GenerateDeviceYieldAndSurplus($deviceId, ManagerRegistry $doctrine) : Response
    {
        $deviceRep = $doctrine->getRepository(Device::class);
        
        $periodRep = $doctrine->getRepository(Period::class);
        $period = $periodRep->getCurrentPeriod();
        $deviceRep->generateRandomYield($deviceId, $period);
        $deviceRep->generateRandomSurplus($deviceId, $period);
        
        dd($deviceRep->fetch($deviceId));

        return $this->render('device/index.html.twig', [
            'controller_name' => 'DeviceController',
        ]);
    }
}
