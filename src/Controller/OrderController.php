<?php

namespace App\Controller;

use App\Entity\Address;
use App\Entity\Order;
use App\Repository\OrderRepository;
use App\Service\CartService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    /**
     * @Route("/order/{id}", name="order")
     * @param Address $address
     * @param CartService $service
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function order(Address $address, CartService $service, EntityManagerInterface $manager): Response
    {
        $order = new Order();
        $order->setUser($this->getUser());
        $order->setAddress($address);
        $order->setCreatedAt(new \DateTimeImmutable());
        $order->setTotal($service->getTotal());

        $manager->persist($order);
        $manager->flush();

        $service->emptyCart();

        return $this->redirectToRoute('cart');
    }

    /**
     * @Route("/orders", name="orders")
     * @return Response
     */
    public function orders(): Response
    {
        return $this->render('order/index.html.twig', ['orders'=>$this->getUser()->getOrders()]);
    }

}
