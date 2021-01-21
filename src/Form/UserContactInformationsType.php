<?php

namespace App\Form;

use App\Entity\Users;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserContactInformationsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('surname')
            ->add('pseudo')
            ->add('email')
            ->add('address')
            ->add('city')
            ->add('postalCode')
            ->add('token')
            ->add('resetToken')
            ->add('password')
            ->add('longitude')
            ->add('latitude')
            ->add('idFacebook')
            ->add('createdAt')
            ->add('updatedAt')
            ->add('roles')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Users::class,
        ]);
    }
}
