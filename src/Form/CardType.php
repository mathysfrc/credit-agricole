<?php

namespace App\Form;

use App\Entity\Card;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CardType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('picture', TextType::class, [
            'label' => 'Image',
            'required' => false,
            'attr' => [
                'placeholder' => 'Image du produit',
                'class' => 'form-control block w-full rounded-md py-1.5 mt-2 text-white bg-[#000000] bg-opacity-10 placeholder:text-gray-400',
            ],
        ])
        ->add('title', TextType::class, [
            'label' => 'Titre',
            'attr' => [
                'placeholder' => 'Carte de visite - Modèle',
                'class' => 'form-control block w-full rounded-md py-1.5 mt-2 text-white bg-[#000000] bg-opacity-10 placeholder:text-gray-400',
            ],
        ])
        ->add('description', TextareaType::class, [
            'label' => 'Description',
            'attr' => [
                'placeholder' => 'Description du produit',
                'class' => 'form-control block w-full rounded-md py-1.5 mt-2 text-white bg-[#000000] bg-opacity-10 placeholder:text-gray-400',
            ],
        ])
        ->add('category', TextType::class, [
            'label' => 'Catégorie',
            'attr' => [
                'placeholder' => 'Carte de visite',
                'class' => 'form-control block w-full rounded-md py-1.5 mt-2 text-white bg-[#000000] bg-opacity-10 placeholder:text-gray-400',
            ],
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Card::class,
        ]);
    }
}
