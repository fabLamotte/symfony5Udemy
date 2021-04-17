<?php

namespace App\Controller;

use App\Classe\Mail;
use App\Entity\ResetPassword;
use App\Entity\User;
use App\Form\ResetPasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ResetPasswordController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;    
    }

    /**
     * @Route("/mot-de-pass-oublie", name="reset_password")
     */
    public function index(Request $request): Response
    {
        if($this->getUser()){
            return $this->redirectToRoute('home');
        }

        if($request->get('email')){
            $user = $this->em->getRepository(User::class)->findOneByEmail($request->get('email'));
            if($user){
                // Etape 1: Enregistrer en base la demande de reset password de l'utilisateur
                $reset_password = new ResetPassword();
                $reset_password->setUser($user);
                $reset_password->setToken(uniqid());
                $reset_password->setCreatedAt(new \DateTime());
                $this->em->persist($reset_password);
                $this->em->flush();
                $this->em->clear();

                // Envoyer un mail à l'utilisateur
                $url = $this->generateUrl('update_password', ['token' => $reset_password->getToken()]);
                $content = "Bonjour " . $user->getFirstname() . "<br />Vous avez demandé à réinitialiser votre mot de passe sur le site La Boutique Française<br/><br/>";
                $content .= "Merci de cliquer sur ce lien suivant pour mettre à jour votre <a href=".$url.">mot de passe</a><br/><br/>";
                $mail = new Mail();
                $mail->send($user->getEmail(), $user->getFirstname() . ' ' . $user->getLastname(), 'Mot de passe oublié', $content);
                $this->addFlash('notice', 'Vous allez recevoir dans quelques secondes un mail avec un lien de modification de mot de passe');
            } else {
                $this->addFlash('notice', 'Adresse email inconnue');
                return $this->redirectToRoute('reset_password');
            }
        }

        return $this->render('reset_password/index.html.twig');
    }

    /**
     * @Route("/modifier-mon-mot-de-passe/{token}", name="update_password")
     */
    public function update($token, Request $request, UserPasswordEncoderInterface $encoder)
    {
        $reset_password = $this->em->getRepository(ResetPassword::class)->findOneByToken($token);
        if(!$reset_password){
            $this->redirectToRoute('reset_password');
        }

        $now = new \DateTime();

        if($now > $reset_password->getCreatedAt()->modify('+ 3 hour')){
            $this->addFlash('notice', 'votre demande de mot de passe a expiré. Merci de la renouveler');
            return $this->redirectToRoute('reset_password');
        }   

        $form = $this->createForm(ResetPasswordType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $new_psd = $form->get('password')->getData();
            $password = $encoder->encodePassword($reset_password->getUser(), $new_psd);
            $reset_password->getUser()->setPassword($password);

            $this->em->flush();

            $this->addFlash('notice', 'Votre mot de passe a bien été mis à jour');
            return $this->redirectToRoute('app_login');
        }
        
        return $this->render('reset_password/update.html.twig', [
            'form' => $form->createView()
        ]);

    }
}
