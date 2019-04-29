<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username')
            ->add(
                'roles', ChoiceType::class, [
                    'choices' => ['ROLE_USER' => 'ROLE_USER', 'ROLE_MANAGER' => 'ROLE_MANAGER', 'ROLE_SUPER_ADMIN' => 'ROLE_SUPER_ADMIN'],
                    'expanded' => false,
                    'multiple' => true,
                ]
            )
            ->add('position');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
