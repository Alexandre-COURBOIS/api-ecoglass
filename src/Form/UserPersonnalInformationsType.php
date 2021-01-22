<?php

namespace App\Form;

use App\Entity\Users;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserPersonnalInformationsType extends ComponentType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, $this->getConfig('Nom*', 'Votre nom'))
            ->add('surname', TextType::class, $this->getConfig('Prénom*', 'Votre prénom'))
            ->add('pseudo', TextType::class, $this->getConfig('Pseudo*', 'Votre pseudo'));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Users::class,
        ]);
    }
}
