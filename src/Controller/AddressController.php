<?php

namespace App\Controller;

use App\Entity\Address;
use App\Form\AddressType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AddressController extends AbstractController
{
    /**
     * @Route("/address", name="address")
     */
    public function index(): Response
    {
        $address = new Address();
        $form = $this->createForm(AddressType::class, $address);
        return $this->renderForm('address/index.html.twig', [
            'form'=>$form
        ]);
    }

    /**
     * @Route("/address/new", name="new_address")
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
        return $this->renderForm('address/index.html.twig', ['form'=>$form]);
    }
}
