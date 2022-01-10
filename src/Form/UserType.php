<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email')
            ->add('roles', ChoiceType::class, [
                'required' => true,
                'multiple' => false,
                'expanded' => false,
                'choices'  => [
                  'ROLE_USER' => 'ROLE_USER',
                  'ROLE_ADMIN' => 'ROLE_ADMIN',
                  'ROLE_SUPERADMIN' => 'ROLE_SUPERADMIN'

                ]

            ])
            ->add('password')
            ->add('name')
            // ->add('createdAt')
            ->add('surname')
            // ->add('folder')
            ->add('displayName')
            // ->add('imgProfile')
            ->add('imgProfile', FileType::class, [
                'mapped' => false,
                'multiple' => false,
                'required' => false,
            ])
        ;

        $builder->get('roles')
            ->addModelTransformer(new CallbackTransformer(
                function ($rolesArray) {
                     // transform the array to a string
                     return count($rolesArray) ? $rolesArray[0] : null;
                },
                function ($rolesString) {
                     // transform the string back to an array
                     return [$rolesString];
                }
            ));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
