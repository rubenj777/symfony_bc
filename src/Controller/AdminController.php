<?php

namespace App\Controller;

use App\Repository\OrderRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        return $this->render('admin/index.html.twig');
    }

    /**
     * @Route("/admin/orders", name="see_orders")
     * @param OrderRepository $repo
     * @return Response
     */
    public function seeOrders(OrderRepository $repo): Response
    {
        $orders = $repo->findAll();
        return $this->render('admin/orders.html.twig', ['orders'=>$orders]);
    }

    /**
     * @Route("/admin/users", name="see_users")
     * @param UserRepository $repo
     * @return Response
     */
    public function seeUsers(UserRepository $repo): Response
    {
        $users = $repo->findAll();
        return $this->render('admin/users.html.twig', ['users'=>$users]);
    }
}
