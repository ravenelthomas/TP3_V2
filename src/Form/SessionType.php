<?php

namespace App\Form;

use App\Entity\Session;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\User;


class SessionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('id_user', EntityType::class, [
            'label' => 'Utilisateurs',
            'class' => User::class,
            'choice_label' => 'fullName',
            'attr' => ['class' => 'form-select w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring focus:border-blue-500'],
        ])
        ->add('start_session', null, [
            'attr' => ['class' => 'form-input w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring focus:border-blue-500'],
        ])
        ->add('end_session', null, [
            'attr' => ['class' => 'form-input w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring focus:border-blue-500'],
        ])
        ->add('commentary', null, [
            'attr' => ['class' => 'form-input w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring focus:border-blue-500'],
        ])
        ->add('response', null, [
            'attr' => ['class' => 'form-input w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring focus:border-blue-500'],
        ])
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Session::class,
        ]);
    }
}
