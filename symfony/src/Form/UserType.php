<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // On définit une variable pour éviter de répéter les classes Tailwind communes
        $inputClass = 'm-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-tertiary focus:ring-tertiary text-sm md:text-xl';
        $labelClass = 'block m-1 text-base  font-bold text-tertiary';

        $builder
            ->add('firstname', TextType::class, [
                'label' => 'Prénom',
                'attr' => ['class' => $inputClass],
                'label_attr' => ['class' => $labelClass],

            ])
            ->add('lastname', TextType::class, [
                'label' => 'Nom',
                'attr' => ['class' => $inputClass],
                'label_attr' => ['class' => $labelClass],
            ])
            ->add('email', EmailType::class, [
                'label' => 'Adresse Email',
                'attr' => ['class' => $inputClass],
                'label_attr' => ['class' => $labelClass],
            ])
            ->add('plainPassword', PasswordType::class, [
                'mapped' => false,
                'required' => false,
                'label' => 'Nouveau mot de passe',
                'attr' => [
                    'class' => $inputClass,
                    'placeholder' => 'Laisser vide pour ne pas modifier',
                    'autocomplete' => 'new-password'
                ],
                'label_attr' => ['class' => $labelClass],
                'help' => 'Si vous remplissez ce champ, votre mot de passe sera mis à jour.',
            ])
            ->add('Adress', TextType::class, [
                'label' => 'Adresse',
                'attr' => ['class' => $inputClass],
                'label_attr' => ['class' => $labelClass],
            ])
            ->add('phone', TextType::class, [
                'label' => 'Téléphone',
                'attr' => ['class' => $inputClass],
                'label_attr' => ['class' => $labelClass]
            ])
            ->add('modifier',SubmitType::class, [
                'label' => 'Mettre à jour le profil',
                'attr' => ['class' => 'mt-4 w-1/3 bg-tertiary text-white py-2 px-4 rounded-md hover:bg-primary transition' ],
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