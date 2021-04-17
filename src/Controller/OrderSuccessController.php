<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderSuccessController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/commande/merci/{stripeSessionId}", name="order_validate")
     */
    public function index($stripeSessionId, Cart $cart): Response
    {
        $order = $this->em->getRepository(Order::class)->findOneByStripeSessionId($stripeSessionId);
        
        if(!$order || $order->getUser() !== $this->getUser()){
            return $this->redirectToRoute('home');
        }

        if(!$order->getState() === 0){
            // Vider la session du panier
                $cart->remove();
            // Modifier le statut isPaid à 1
                $order->setState(1);
                $this->em->flush();

            // Envoyer un mail à notre client pour lui confirmer sa commande
        }
        // Afficher les quelques informations de la cmmande de l'utilisateur


        return $this->render('order_success/index.html.twig',[
            'order' => $order
        ]);
    }
}
