<?php

namespace App\Form;

use App\Entity\Adresse;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => false,
                'attr'  => [
                    'placeholder' => 'Donnez un nom à votre adresse'
                ]
            ])
            ->add('firstname', TextType::class, [
                'label' => false,
                'attr'  => [
                    'placeholder' => 'Prénom'
                ]
            ])
            ->add('lastname', TextType::class, [
                'label' => false,
                'attr'  => [
                    'placeholder' => 'Nom'
                ]
            ])
            ->add('company', TextType::class, [
                'label' => false,
                'required'=> false,
                'attr'  => [
                    'placeholder' => 'Votre entreprise'
                ]
            ])
            ->add('adresse', TextType::class, [
                'label' => false,
                'attr'  => [
                    'placeholder' => 'Votre adresse'
                ]
            ])
            ->add('codePostal', TextType::class, [
                'label' => false,
                'attr'  => [
                    'placeholder' => 'Votre code postal'
                ]
            ])
            ->add('city', TextType::class, [
                'label' => false,
                'attr'  => [
                    'placeholder' => 'Ville'
                ]
            ])
            ->add('pays', CountryType::class, [
                'label' => false,
                'attr'  => [
                    'placeholder' => 'Pays'
                ]
            ])
            ->add('phone', TelType::class, [
                'label' => false,
                'attr'  => [
                    'placeholder' => 'Numéro de téléphone'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Ajouter mon adresse',
                'attr' => [
                    'class' => 'btn btn-block btn-success'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Adresse::class,
        ]);
    }
}
