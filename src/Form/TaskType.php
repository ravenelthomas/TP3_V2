<?php

namespace App\Form;

use App\Entity\Task;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TaskType extends AbstractType
{
    
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('name', null, [
            'attr' => ['class' => 'form-input w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring focus:border-blue-500'],
        ])
        ->add('description', null, [
            'attr' => ['class' => 'form-input w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring focus:border-blue-500'],
        ])
        ->add('id_session', null, [
            'attr' => ['class' => 'form-input w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring focus:border-blue-500'],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Task::class,
        ]);
    }
}
