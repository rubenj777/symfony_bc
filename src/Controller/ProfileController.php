<?php

namespace App\Controller;

use App\Entity\Address;
use App\Form\AddressType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    /**
     * @Route("/profile", name="profile")
     * @return Response
     */
    public function index(): Response
    {
        $address = new Address();
        $form = $this->createForm(AddressType::class, $address);
        return $this->renderForm('profile/index.html.twig', [
            'form'=>$form,
        ]);
    }

    /**
     * @Route("/profile/address/new", name="new_address")
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function newAddress(Request $request, EntityManagerInterface $manager): Response
    {
        $address = new Address();
        $form = $this->createForm(AddressType::class, $address);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $address->setUser($this->getUser());
            $manager->persist($address);
            $manager->flush();
        }
        return $this->renderForm('profile/index.html.twig', ['form'=>$form]);
    }
}
