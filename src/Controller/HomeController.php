<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/home", name="app_home")
     */
    public function index(ProductRepository $repository): Response
    {
        $products = $repository->findAll();

        return $this->render('home/index.html.twig', [
            'products'=>$products,
        ]);
    }
}
