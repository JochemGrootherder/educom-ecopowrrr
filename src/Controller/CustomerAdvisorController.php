<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\Persistence\ManagerRegistry;

use App\Entity\CustomerAdvisor;

use App\Repository\CustomerAdvisorRepository;

#[Route('/customer_advisor')]
class CustomerAdvisorController extends AbstractController
{
    #[Route('/', name: 'customer_advisor')]
    public function index(): Response
    {
        return $this->render('customer_advisor/index.html.twig', [
            'controller_name' => 'CustomerAdvisorController',
        ]);
    }

    #[Route('/create', name: 'CreateCustomerAdvisor')]
    public function createCustomerAdvisor(ManagerRegistry $doctrine)
    {
        $customerAdvisor = 
        [
            "username" => "BroodjeKroket",
            "password" => "Kroket123!"
        ];

        $rep = $doctrine->getRepository(CustomerAdvisor::class);
        $result = $rep->saveCustomerAdvisor($customerAdvisor);
        dd( $result);
    }
}
