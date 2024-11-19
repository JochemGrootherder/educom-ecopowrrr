<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\Persistence\ManagerRegistry;

use App\Entity\DeviceManager;

#[Route('/DeviceManager')]
class DeviceManagerController extends AbstractController
{
    #[Route('/', name: 'device_manager')]
    public function index(): Response
    {
        return $this->render('device_manager/index.html.twig', [
            'controller_name' => 'DeviceManagerController',
        ]);
    }
    #[Route('/send', name: 'handleMessage')]
    public function handleMessage(Request $request, ManagerRegistry $doctrine) : Response
    {
        $data = json_decode($request->getContent(), true);

        $deviceManagerRep = $doctrine->getRepository(DeviceManager::class);
        $deviceManagerRep->storeMessageData($data);

        $response = new Response(json_encode($data));
        return $response;
    }
}
