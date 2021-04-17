<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Order;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Symfony\Component\HttpFoundation\JsonResponse;

class StripeController extends AbstractController
{
    /**
     * @Route("/commande/create_session/{reference}", name="stripe_create_session")
     */
    public function index(EntityManagerInterface $em, Cart $cart, $reference): Response
    {
        $product_for_stripe = [];
        $YOUR_DOMAIN = 'http://127.0.0.1:8000/';

        $order = $em->getRepository(Order::class)->findOneByReference($reference);
        if(!$order){
            $response = new JsonResponse(['error' => 'order']);
            return $response;
        }
        
        foreach($order->getOrderDetails()->getValues() as $product){
            $product_object = $em->getRepository(Product::class)->findOneByName($product->getProduct());
            $product_for_stripe[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'unit_amount' => $product->getPrice(),
                    'product_data' => [
                        'name' => $product->getProduct(),
                        'images' => [$YOUR_DOMAIN."uploads/".$product_object->getIllustration()],
                    ],
                ],
                'quantity' => $product->getQuantity(),
            ];
        }

        $product_for_stripe[] = [
            'price_data' => [
                'currency' => 'eur',
                'unit_amount' => $order->getCarrierPrix(),
                'product_data' => [
                    'name' => $order->getCarrierName(),
                    'images' => [$YOUR_DOMAIN],
                ],
            ],
            'quantity' => 1,
        ];
        
        \Stripe\Stripe::setApiKey('sk_test_51IgQKuJllaqyb2RYLLPU9exP5HXayoehLeaKcE2oc3t8s7F8EXyDxaCz1r7As5RfaRTOUUxJGvhhiqAfocsNy0QC00RAKCIDvA');

        $checkout_session = Session::create([
            'customer_email'    => $this->getUser()->getEmail(),
            'payment_method_types' => ['card'],
            'line_items' => [$product_for_stripe],
            'mode' => 'payment',
            'success_url' => $YOUR_DOMAIN . 'commande/merci/{CHECKOUT_SESSION_ID}',
            'cancel_url' => $YOUR_DOMAIN . 'commande/erreur/{CHECKOUT_SESSION_ID}',
        ]);

        $order->setStripeSessionId($checkout_session->id);
        $em->flush();

        $response = new JsonResponse(['id' => $checkout_session->id]);
        return $response;
    }
}
