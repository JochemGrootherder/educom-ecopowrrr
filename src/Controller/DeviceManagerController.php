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

    #[Route('/createMessage', name: 'createMessage')]
    public function createMessage(Request $request, ManagerRegistry $doctrine) : Response
    {
        $requestData = json_decode($request->getContent(), true);
        $deviceManagerId = $requestData['deviceManagerId'];

        $deviceManagerRep = $doctrine->getRepository(DeviceManager::class);
        $deviceManager = $deviceManagerRep->fetch($deviceManagerId);
        $deviceManager->generateRandomUsage();

        $startDate = date_create_from_format("Y-m-d", $requestData['startDate']);
        $startDate->setTime(0,0);
        $endDate = date_create_from_format("Y-m-d", $requestData['endDate']);
        $endDate->setTime(0,0);
        $message = $deviceManager->createMessage($startDate, $endDate);
        
        $response = new Response($message); 
        return $response;
        
        
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
}
