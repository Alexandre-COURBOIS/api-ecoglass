<?php

namespace App\Form;

use App\Entity\Users;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserContactInformationsType extends ComponentType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, $this->getConfig('Email*', 'Votre email'))
            ->add('address', TextType::class, $this->getConfig('Adresse', 'Si vous souhaitez nous indiquer votre adresse'))
            ->add('city', TextType::class, $this->getConfig('Ville*', 'Votre ville de résidence'))
            ->add('postalCode', TextType::class, $this->getConfig('Code postal', 'Si vous souhaitez nous indiquer votre code postal'))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Users::class,
        ]);
    }
}
