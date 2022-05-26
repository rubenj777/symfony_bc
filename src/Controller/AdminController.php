<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\OrderRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
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
    public function seeOrders(OrderRepository $repo, UserRepository $userRepository): Response
    {
        $orders = $repo->findAll();
        $users = $userRepository->findAll();
        return $this->render('admin/orders.html.twig', ['orders'=>$orders, 'users'=>$users]);
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

    /**
     * @Route("/admin/newuser", name="new_user")
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param UserPasswordHasherInterface $userPasswordHasher
     * @return Response
     */
    public function newUser(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $user = new User;
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $user->setCreatedAt(new \DateTimeImmutable());
            $user->setRoles(["ROLE_USER"]);

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('admin');
        }
        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/order/{id}/status", name="change_status")
     * @param Request $request
     * @param OrderRepository $orderRepository
     * @param EntityManagerInterface $manager
     * @return RedirectResponse
     */
    public function changeStatus(Request $request, OrderRepository $orderRepository, EntityManagerInterface $manager): RedirectResponse
    {
        $order_id = $request->get('id');
        $order = $orderRepository->find($order_id);
        $order->setStatus(1);
        $manager->persist($order);
        $manager->flush();

        return $this->redirectToRoute('see_orders');
    }
}
