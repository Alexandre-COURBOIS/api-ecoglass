<?php

namespace App\Form;

use App\Entity\Users;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserRegisterType extends ComponentType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, $this->getConfig('Nom*', 'Votre nom'))
            ->add('surname', TextType::class, $this->getConfig('Prénom*', 'Votre prénom'))
            ->add('pseudo', TextType::class, $this->getConfig('Pseudo*', 'Votre pseudo'))
            ->add('email', EmailType::class, $this->getConfig('Email*', 'Votre email'))
            ->add('address', TextType::class, $this->getConfig('Adresse', 'Si vous souhaitez nous indiquer votre adresse'))
            ->add('city', TextType::class, $this->getConfig('Ville*', 'Votre ville de résidence'))
            ->add('postalCode', TextType::class, $this->getConfig('Code postal', 'Si vous souhaitez nous indiquer votre code postal'))
            ->add('password', PasswordType::class, $this->getConfig('Mot de passe', 'Votre mot de passe'))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Users::class,
        ]);
    }
}
