<?php

namespace App\Controller;

use App\Repository\CartRepository;
use App\Repository\OrderItemRepository;
use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use DateTime;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/api")
 */
class ApiController extends AbstractController
{
    /**
     * @Route("/products", name="products")
     */
    public function getAllProducts(ProductRepository $repo): Response
    {
        $data['products'] = $repo->findAllProducts();
        return $this->json($data,200, [], ['groups'=>'products:read']);
    }

//    /**
//     * @Route("/mostSoldProducts", name="most-sold-products")
//     */
//    public function getMostSoldProducts(OrderItemRepository $repo): JsonResponse
//    {
//        $data['products'] = $repo->findMostSold();
//        return $this->json($data, 200, [], ['groups'=>'order-item:read']);
//    }

    /**
     * @Route("/from/{begin}/to/{end}", name="total-between-date")
     * @param DateTime $begin
     * @param DateTime $end
     * @param OrderRepository $orderRepository
     * @param CartRepository $cartRepository
     * @return JsonResponse
     */
    public function getDataBetweenDate(DateTime $begin, DateTime $end, OrderRepository $orderRepository, CartRepository $cartRepository, UserRepository $userRepository,
    Session $session): JsonResponse
    {
        $data = [];

        $total = 0;
        foreach($orderRepository->findFromDateToDate($begin, $end) as $order) {
            $total += $order->getTotal();
        }
        $data['total'] = $total;
        $data['nbOrders'] = count($orderRepository->findFromDateToDate($begin, $end));
        $data['nbCarts'] = count($cartRepository->findFromDateToDate($begin, $end));

        foreach($cartRepository->findFromDateToDate($begin, $end) as $cart) {
            $data['avgCart'] = $cartRepository->getAvg();
        }
        $data['newClients'] = count($userRepository->findFromDateToDate($begin, $end));
        $data['cancelCart'] = 100 * count($cartRepository->findFromDateToDate($begin, $end)) / (count($orderRepository->findFromDateToDate($begin, $end)) + count($cartRepository->findFromDateToDate($begin, $end)));



        return $this->json($data, 200);
    }
}
