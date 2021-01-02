<?php

namespace App\Controller;

use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;


/**
 * @Route("/register", name="register_")
 * Class RegisterController
 * @package App\Controller
 */
class RegisterController extends AbstractController
{

    public function __construct(){}

    /**
     * @Route("/user", name="user")
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

        $this->createForm();


    }

}
