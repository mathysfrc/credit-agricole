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
                    'class' => 'form-control block w-full rounded-md py-1.5 text-white bg-[#000000] bg-opacity-10 placeholder:text-gray-400',
                ],
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'label' => ' Accepter les conditions ',
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
                    'class' => 'form-control block w-full rounded-md py-1.5 text-white bg-[#000000] bg-opacity-10 placeholder:text-gray-400',
                    'placeholder' => 'Mot de passe',
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
                    'class' => 'form-control block w-full rounded-md py-1.5 text-white bg-[#000000] bg-opacity-10 placeholder:text-gray-400'
                ],
            ])
            ->add('last_name', TextType::class, [
                'label' => 'Nom',
                'mapped' => false,
                'attr' => [
                    'placeholder' => 'Dupont',
                    'class' => 'form-control block w-full rounded-md py-1.5 text-white bg-[#000000] bg-opacity-10 placeholder:text-gray-400'
                ],
            ])
            ->add('company', TextType::class, [
                'label' => 'Entreprise',
                'mapped' => false,
                'attr' => [
                    'placeholder' => 'ISF Imprimerie',
                    'class' => 'form-control block w-full rounded-md py-1.5 text-white bg-[#000000] bg-opacity-10 placeholder:text-gray-400',
                ],
            ])
            ->add('fonction', TextType::class, [
                'label' => 'Fonction',
                'mapped' => false,
                'attr' => [
                    'placeholder' => 'Développeur web',
                    'class' => 'form-control block w-full rounded-md py-1.5 text-white bg-[#000000] bg-opacity-10 placeholder:text-gray-400',
                ],
            ])
            ->add('mobile_phone', TelType::class, [
                'label' => 'Numéro de téléphone',
                'mapped' => false,
                'required' => false,
                'attr' => [
                    'class' => 'form-control block w-full rounded-md py-1.5 text-white bg-[#000000] bg-opacity-10 placeholder:text-gray-400',
                    'placeholder' => '00 00 00 00 00',
                ]
            ])
            ->add('house_phone', TelType::class, [
                'label' => 'Téléphone fixe',
                'mapped' => false,
                'required' => false,
                'attr' => [
                    'class' => 'form-control block w-full rounded-md py-1.5 text-white bg-[#000000] bg-opacity-10 placeholder:text-gray-400',
                    'placeholder' => '00 00 00 00 00',
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
