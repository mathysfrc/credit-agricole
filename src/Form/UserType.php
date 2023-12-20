<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserType extends AbstractType
{

    public const ROLES = [
        'USER' => 'ROLE_USER',
        'ADMIN' => 'ROLE_ADMIN',
    ];

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'required' => true,
                'attr' => [
                    'class' => 'form-control block w-full rounded-md py-1.5 mt-2 text-white bg-[#000000] bg-opacity-10 placeholder:text-gray-400',
                ],
            ])
            ->add('roles', ChoiceType::class, [
                'expanded' => true,
                'multiple' => true,
                'choices' => self::ROLES,
                'label' => 'Rôle',
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Mot de passe',
                'required' => false,
                'mapped' => false,
                'attr' => [
                    'class' => 'form-control block w-full rounded-md py-1.5 mt-2 text-white bg-[#000000] bg-opacity-10 placeholder:text-gray-400',
                ],
            ])
            ->add('isVerified', CheckboxType::class, [
                'required' => false,
                'label' => 'Vérifié ?',
                'attr' => [
                    'class' => 'flex rounded mt-2'
                ]
            ])

            ->add('firstName', TextType::class, [
                'label' => 'Prénom',
                'required' => true,
                'attr' => [
                    'class' => 'form-control block w-full rounded-md py-1.5 mt-2 text-white bg-[#000000] bg-opacity-10 placeholder:text-gray-400',
                ],
            ])
            ->add('lastName', TextType::class, [
                'label' => 'Nom',
                'required' => true,
                'attr' => [
                    'class' => 'form-control block w-full rounded-md py-1.5 mt-2 text-white bg-[#000000] bg-opacity-10 placeholder:text-gray-400',
                ],
            ])
            ->add('company', TextType::class, [
                'label' => 'Entreprise',
                'required' => true,
                'attr' => [
                    'class' => 'form-control block w-full rounded-md py-1.5 mt-2 text-white bg-[#000000] bg-opacity-10 placeholder:text-gray-400',
                ],
            ])
            ->add('fonction', TextType::class, [
                'label' => 'Fonction',
                'required' => true,
                'attr' => [
                    'class' => 'form-control block w-full rounded-md py-1.5 mt-2 text-white bg-[#000000] bg-opacity-10 placeholder:text-gray-400',
                ],
            ])
            ->add('mobilePhone', TelType::class, [
                'label' => 'Téléphone mobile',
                'required' => false,
                'attr' => [
                    'class' => 'form-control block w-full rounded-md py-1.5 mt-2 text-white bg-[#000000] bg-opacity-10 placeholder:text-gray-400',
                ],
            ])
            ->add('housePhone', TelType::class, [
                'label' => 'Téléphone fixe',
                'required' => false,
                'attr' => [
                    'class' => 'form-control block w-full rounded-md py-1.5 mt-2 text-white bg-[#000000] bg-opacity-10 placeholder:text-gray-400',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
