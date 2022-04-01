<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/api")
 */
class ApiController extends AbstractController
{
    /**
     * @Route("/products", name="products")
     */
    public function index(ProductRepository $repo): Response
    {
        $data = [];
        $data['products'] = $repo->findAll();
        return $this->json($data, 200, [], ['groups'=>'products-read']);
    }
}
