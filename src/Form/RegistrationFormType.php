<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'attr' => [
                    'placeholder' => 'example@example.com',
                    'class' => 'block w-full rounded-md border-0 py-1.5 text-white bg-[#000000] bg-opacity-10 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6',
                ],
            ])
            ->add('agreeTerms', CheckboxType::class, [
                                'mapped' => false,
                                'label' => 'Accepter les conditions',
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                'always_empty' => false,
                'mapped' => false,
                'attr' => [
                    'autocomplete' => 'new-password',
                    'class' => 'block w-full rounded-md border-0 py-1.5 text-white bg-[#000000] bg-opacity-10 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:max-w-xs sm:text-sm sm:leading-6',
                ],
                'label' => 'Mot de passe',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Entrez un mot de passe',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('first_name', TextType::class, [
                'label' => 'Prénom',
                'mapped' => false,
                'attr' => [
                    'placeholder' => 'Jean',
                    'class' => 'block w-full rounded-md border-0 py-1.5 text-white bg-[#000000] bg-opacity-10 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6'
                ],
            ])
            ->add('last_name', TextType::class, [
                'label' => 'Nom',
                'mapped' => false,
                'attr' => [
                    'placeholder' => 'Dupont',
                    'class' => 'block w-full rounded-md border-0 py-1.5 text-white bg-[#000000] bg-opacity-10 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6'
                ],
            ])
            ->add('company', TextType::class, [
                'label' => 'Entreprise',
                'mapped' => false,
                'attr' => [
                    'placeholder' => 'ISF Imprimerie',
                    'class' => 'block w-full rounded-md border-0 py-1.5 text-white bg-[#000000] bg-opacity-10 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:max-w-xs sm:text-sm sm:leading-6',
                ],
            ])
            ->add('fonction', TextType::class, [
                'label' => 'Fonction',
                'mapped' => false,
                'attr' => [
                    'placeholder' => 'Développeur web',
                    'class' => 'block w-full rounded-md border-0 py-1.5 text-white bg-[#000000] bg-opacity-10 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:max-w-xs sm:text-sm sm:leading-6',
                ],
            ])
            ->add('mobile_phone', TelType::class, [
                'label' => 'Numéro de téléphone',
                'mapped' => false,
                'required' => false,
                'attr' => [
                    'class' => 'block w-full rounded-md border-0 py-1.5 text-white bg-[#000000] bg-opacity-10 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:max-w-xs sm:text-sm sm:leading-6',
                ]
            ])
            ->add('house_phone', TelType::class, [
                'label' => 'Téléphone fixe',
                'mapped' => false,
                'required' => false,
                'attr' => [
                    'class' => 'block w-full rounded-md border-0 py-1.5 text-white bg-[#000000] bg-opacity-10 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:max-w-xs sm:text-sm sm:leading-6',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
