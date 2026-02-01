<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $inputClass = 'w-full  px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none';
        $labelClass = 'block text-gray-700 mb-1';

        $builder
            ->add('email', null, [
                'label' => 'Email',
                'label_attr' => ['class' => $labelClass],
                'attr' => [
                    'autocomplete' => 'email',
                    'class' => $inputClass,
                    'placeholder' => 'you@example.com',
                ],
            ])
            ->add('firstname', null, [
                'label' => 'Prénom',
                'label_attr' => ['class' => $labelClass],
                'attr' => ['class' => $inputClass],
            ])
            ->add('lastname', null, [
                'label' => 'Nom',
                'label_attr' => ['class' => $labelClass],
                'attr' => ['class' => $inputClass],
            ])
            ->add('Adress', null, [
                'label' => 'Adresse',
                'label_attr' => ['class' => $labelClass],
                'attr' => ['class' => $inputClass],
            ])
            ->add('phone', null, [
                'label' => 'Téléphone',
                'label_attr' => ['class' => $labelClass],
                'attr' => ['class' => $inputClass],
            ])
            ->add('plainPassword', PasswordType::class, [
                'label' => 'Mot de passe',
                'label_attr' => ['class' => $labelClass],
                'mapped' => false,
                'attr' => [
                    'autocomplete' => 'new-password',
                    'class' => $inputClass,
                    'placeholder' => '••••••••',
                ],
                'constraints' => [
                    new NotBlank(message: 'Un mot de passe est requis.'),
                    new Length(
                        min: 6,
                        minMessage: 'Votre mot de passe doit contenir au moins {{ limit }} caractères',
                        max: 4096
                    ),
                ],
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'label' => 'J’accepte les conditions d’utilisation',
                'label_attr' => ['class' => 'text-gray-700 ml-2'],
                'mapped' => false,
                'attr' => [
                    'class' => 'h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500',
                ],
                'constraints' => [
                    new IsTrue(message: 'Vous devez accepter les conditions d\'utilisation.'),
                ],
            ])
            ->add('envoyez', SubmitType::class, [
                'label' => 'Créer mon compte',
                'attr' => [
                    'class' => 'w-full  bg-tertiary text-text py-2 rounded-lg hover:bg-secondary transition mt-4',
                ],
            ])
        ;
    }
}
