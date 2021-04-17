<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Adresse;
use App\Form\AdressType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AccountAdresseController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    /**
     * @Route("/compte/adresse", name="account_adresse")
     */
    public function index(): Response
    {
        return $this->render('account/adresse.html.twig');
    }

    /**
     * @Route("/compte/ajouter-une-adresse", name="account_adresse_add")
     */
    public function add(Cart $cart, Request $request): Response
    {
        $adresse = new Adresse();
        $form = $this->createForm(AdressType::class, $adresse);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $adresse->setUser($this->getUser());
            $this->em->persist($adresse);
            $this->em->flush();
            
            if($cart->get()){
                return $this->redirectToRoute('order');
            } else {
                return $this->redirectToRoute('account_adresse');
            }
        }


        return $this->render('account/adresse_form.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/compte/modifier-une-adresse/{id}", name="account_adresse_edit")
     */
    public function edit(Request $request, $id): Response
    {
        $adresse = $this->em->getRepository(Adresse::class)->findOneById($id);
        if(!$adresse || $adresse->getUser() != $this->getUser()) {
            return $this->redirectToRoute('account_adresse');
        }        

        $form = $this->createForm(AdressType::class, $adresse);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $this->em->flush();

            return $this->redirectToRoute('account_adresse');
        }


        return $this->render('account/adresse_form.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/compte/supprimer-une-adresse/{id}", name="account_adresse_delete")
     */
    public function delete($id): Response
    {
        $adresse = $this->em->getRepository(Adresse::class)->findOneById($id);

        if($adresse && $adresse->getUser() === $this->getUser()) {
            $this->em->remove($adresse);
            $this->em->flush();
        }        

        return $this->redirectToRoute("account_adresse");
    }
}
