<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
{
    $builder
        ->add('email', null, [
            'attr' => ['class' => 'form-input w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring focus:border-blue-500'],
        ])
        ->add('name', null, [
            'attr' => ['class' => 'form-input w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring focus:border-blue-500'],
        ])
        ->add('surname', null, [
            'attr' => ['class' => 'form-input w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring focus:border-blue-500'],
        ]);
}


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
