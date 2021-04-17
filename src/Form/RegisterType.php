<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Symfony\Component\Validator\Constraints\Length;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', TextType::class, [
                'label' => false,
                'attr'  => [
                    'placeholder'=> 'Prénom'
                ],
                'constraints' => new Length([
                    'min'   =>2,
                    'max'   =>30,
                ]),
            ])
            ->add('lastname', TextType::class, [
                'label' => false,
                'attr'  => [
                    'placeholder'=> 'Nom'
                ],
                'constraints' => new Length([
                    'min'   =>2,
                    'max'   =>30,
                ]),
            ])
            ->add('email', EmailType::class, [
                'label' => false,
                'attr'  => [
                    'placeholder'=> 'Adresse de messagerie'
                ],
                'constraints' => new Length([
                    'min'   =>2,
                    'max'   =>80,
                ]),
            ])
            ->add('password', RepeatedType::class, [
                'type'              => PasswordType::class,
                'invalid_message'   => 'Le mot de passe et la confirmation doivent être identique',
                'label'             => false,
                'required'          => true,
                'first_options'     => ['attr'=> ['placeholder' => 'Mot de passe'], 'label' => false],
                'second_options'     => ['attr'=> ['placeholder' => 'Confirmer mot de passe'], 'label' => false],
            ])
            ->add('submit', SubmitType::class, [
                'label'=>'S\'inscrire',
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
