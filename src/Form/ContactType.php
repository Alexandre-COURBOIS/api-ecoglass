<?php

namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends ComponentType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, $this->getConfig("Nom", 'Votre Nom'))
            ->add('prenom', TextType::class, $this->getConfig("Prénom", 'Votre Prénom'))
            ->add('email', EmailType::class, $this->getConfig("Votre email", "Votre email"))
            ->add('message', TextareaType::class, $this->getConfig("Votre message", "Renseigner votre message"));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
