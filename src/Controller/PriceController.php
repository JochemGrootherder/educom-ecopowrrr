<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\Persistence\ManagerRegistry;

use App\Entity\Price;

#[Route('/price')]
class PriceController extends AbstractController
{
    private $doctrine;
    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    #[Route('/', name: 'price')]
    public function index(): Response
    {
        return $this->render('price/index.html.twig', [
            'controller_name' => 'PriceController',
        ]);
    }

    #[Route("/id={customer_id}/price={price}", name: "update_price", requirements:['customer_id' => '\d+', 'price' => '(\d+)(.|,)(\d{2})'])]
    public function updatePrice($customer_id, $price) : Response
    {
        $priceRep = $this->doctrine->getRepository(Price::class);
        $priceArr = [
            "customer_id" => $customer_id,
            "price" => $price
        ];
        $priceRep->savePrice($priceArr);
    }
}
