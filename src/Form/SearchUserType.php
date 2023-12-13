<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->setMethod('GET')
        ->add('search', SearchType::class, [
            'label' => ' ',
            'attr' => [
                'class' => 'bg-darkblue border-0',
                'placeholder' => 'Rechercher',
            ]
            // Autres options du champ de recherche
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'csrf_protection' => true,
            'data_class' => null,
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }
}