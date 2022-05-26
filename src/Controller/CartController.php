<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\Product;
use App\Entity\User;
use App\Repository\AddressRepository;
use App\Service\CartService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController

{
    /**
     * @Route("/cart", name="cart")
     */
    public function index(AddressRepository $repo, CartService $service): Response
    {
        $addresses = $repo->findAll();
        return $this->render('cart/index.html.twig', ['addresses'=>$addresses, 'cart'=>$service->getCart(), 'total'=>$service->getTotal()]);
    }


    /**
     * @Route("/cart/add/{id}", name="cart_add")
     * @param Product $product
     * @param CartService $service
     * @param EntityManagerInterface $manager
     * @return RedirectResponse
     */
    public function add(Product $product, CartService $service, EntityManagerInterface $manager): RedirectResponse
    {
        if($product) {
            $service->add($product);

            $cart = $service->isInDatabase();
           // dd($cart);
            if(!$cart) {
                $cart = new Cart();
                if($this->getUser()) {
                    $cart->setUser($this->getUser());
                }
                $cart->setSessionId($service->getSessionId());
            }


            $cart->setCreatedAt(new \DateTimeImmutable());
            $cart->setTotal($service->getTotal());
            $manager->persist($cart);
            $manager->flush();
        }
        return $this->redirectToRoute('cart');
    }

    /**
     * @Route("/cart/delete/{id}", name="cart_delete")
     * @param Product $product
     * @param CartService $service
     * @return RedirectResponse
     */
    public function delete(Product $product, CartService $service): RedirectResponse
    {
        if($product) {
            $service->delete($product);
        }
        return $this->redirectToRoute('cart');
    }

    /**
     * @Route("/cart/deleterow/{id}", name="cart_delete_row")
     * @param Product $product
     * @param CartService $service
     * @return RedirectResponse
     */
    public function deleteRow(Product $product, CartService $service): RedirectResponse
    {
        if($product) {
            $service->deleteRow($product);
        }
        return $this->redirectToRoute('cart');
    }

    /**
     * @Route("/cart/emptycart", name="empty_cart")
     * @param CartService $service
     * @return RedirectResponse
     */
    public function emptyCart(CartService $service): RedirectResponse
    {
        $service->emptyCart();
        return $this->redirectToRoute('app_home');
    }
}
