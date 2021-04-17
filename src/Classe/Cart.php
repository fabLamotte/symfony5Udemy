<?php 

namespace App\Classe;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Entity\Product;

class Cart
{
    private $session;
    private $em;

    public function __construct(EntityManagerInterface $em, SessionInterface $session)
    {
        $this->session = $session;
        $this->em = $em;
    }

    public function add($id)
    {
        $cart = $this->session->get('cart', []);

        if(!empty($cart[$id])){
            $cart[$id]++;
        } else {
            $cart[$id] = 1;
        }

        $this->session->set('cart', $cart);
    }

    public function get()
    {
        return $this->session->get('cart');
    }

    public function remove()
    {
        return $this->session->remove('cart');
    }

    public function delete($id)
    {
        $cart = $this->session->get('cart', []);
        unset($cart[$id]);

        return $this->session->set('cart', $cart);
    }

    public function decrease($id)
    {
        $cart = $this->session->get('cart', []);
        if($cart[$id] > 1){
            $cart[$id]--;
        } else {
            unset($cart[$id]);
        }

        return $this->session->set('cart', $cart);
    }

    public function getFull(){
        $cartComplete = [];

        if($this->get()){
            foreach($this->get() as $id => $quantity){
                $product = $this->em->getRepository(Product::class)->findOneById($id);
                if($product){
                    $cartComplete[] = [
                        'product'   => $product,
                        'quantity'  => $quantity,
                    ];
                } else {
                    $this->delete($id);
                }
            }
        }

        return $cartComplete;
    }

}