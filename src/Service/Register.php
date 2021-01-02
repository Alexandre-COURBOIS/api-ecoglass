<?php

namespace App\Service;

use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class Register
{

    /**
     * @param Request $request
     * @param ValidatorInterface $validator
     * @param EntityManagerInterface $em
     * @param UserPasswordEncoderInterface $passwordEncoder
     *
     * Methode permettant l'inscription de nouveaux utilisateurs
     */
    public function userRegister(Request $request, ValidatorInterface $validator, EntityManagerInterface $em, UserPasswordEncoderInterface $passwordEncoder)
    {
        $user = new Users();

        $data = json_decode($request->getContent(), true);


    }

}