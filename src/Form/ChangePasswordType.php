<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'disabled'  => true,
                'label'     => 'Adresse de messagerie',
            ])
            ->add('firstname', TextType::class, [
                'disabled'  => true,
                'label'     => 'Prénom',
            ])
            ->add('lastname', TextType::class, [
                'disabled'  => true,
                'label'     => 'Nom',
            ])
            ->add('old_password', PasswordType::class, [
                'label'     => 'Mon mot de passe',
                'mapped'    => false,
                'attr'      => [
                    'placeholder'   => 'Veuillez saisir votre mot de passe actuel'
                ]
            ])
            ->add('new_password', RepeatedType::class, [
                'type'              => PasswordType::class,
                'invalid_message'   => 'Le mot de passe et la confirmation doivent être identique',
                'label'             => 'Mon nouveau mot de passe',
                'mapped'            => false,
                'required'          => true,
                'first_options'     => ['attr'=> ['placeholder' => 'Nouveau mot de passe'], 'label' => false],
                'second_options'     => ['attr'=> ['placeholder' => 'Confirmer nouveau mot de passe'], 'label' => false],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Mettre à jour'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
