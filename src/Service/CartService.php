<?php


namespace App\Service;

use App\Entity\Cart;
use App\Entity\Product;
use App\Entity\User;
use App\Repository\CartRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartService
{
    private $session;
    private $repo;
    private $cartRepo;
    private $manager;


    /**
     * @param ProductRepository $repo
     * @param CartRepository $cartRepo
     * @param SessionInterface $session
     * @param EntityManagerInterface $manager
     */
    public function __construct(ProductRepository $repo, CartRepository $cartRepo, SessionInterface $session, EntityManagerInterface $manager)
    {
        $this->session = $session;
        $this->repo = $repo;
        $this->cartRepo = $cartRepo;
        $this->manager = $manager;
    }

    /**
     * @return array
     */
    public function getCart(): array
    {

        $cart = $this->session->get('cart', []);
        $data = [];

        foreach ($cart as $id=>$qty) {
            $item = [
                'product'=>$this->repo->find($id),
                'qty'=>$qty
            ];
            $data[]=$item;
        }
        return $data;
    }

    /**
     * @return float|int
     */
    public function getTotal()
    {
        $total = 0;
        foreach ($this->getCart() as $item) {
            $total += ($item['product']->getPrice() * $item['qty']);
        }
        return $total;
    }

    /**
     * @param Product $product
     * @return void
     */
    public function add(Product $product)
    {
        $cart = $this->session->get('cart', []);
        $id = $product->getId();

        if(isset($cart[$id])) {
            $cart[$id]++;
        } else {
            $cart[$id] = 1;
        }

        $this->session->set('cart', $cart);
    }

    /**
     * @param Product $product
     * @return void
     */
    public function delete(Product $product)
    {
        $id = $product->getId();
        $cart = $this->session->get('cart', []);
        if(isset($cart[$id])) {
            $cart[$id]--;
            if($cart[$id] == 0) {
                unset($cart[$id]);
            }
        }
        if($cart == []) {
            $this->emptyCart();
        } else {
            $this->session->set('cart', $cart);
        }
    }

    /**
     * @param Product $product
     * @return void
     */
    public function deleteRow(Product $product)
    {
        $cart = $this->session->get('cart', []);
        $id = $product->getId();

        if(isset($cart[$id])) {
            unset($cart[$id]);
        }

        if($cart == []) {
            $this->emptyCart();
        } else {
            $this->session->set('cart', $cart);
        }
    }

    /**
     * @return void
     */
    public function emptyCart()
    {
        $this->session->remove('cart');
    }

    /**
     * @return int|mixed
     */
    public function count()
    {
        $count = 0;
        $cart = $this->session->get('cart', []);

        foreach($cart as $id=>$qty) {
            $count+=$qty;
        }
        return $count;
    }

    /**
     * @return string
     */
    public function getSessionId(): string
    {
        return $this->session->getId();

    }

    /**
     * @return Cart|null
     */
    public function isInDatabase(): ?Cart
    {
        return $this->cartRepo->findOneBy(['session_id'=>$this->getSessionId()]);
    }
}