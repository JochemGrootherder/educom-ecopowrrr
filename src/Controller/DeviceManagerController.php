<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

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
    public function handleMessage(Request $request) : Response
    {
        $data = json_decode($request->getContent());

        $response = new Response(json_encode($data));
        return $response;
    }
}
