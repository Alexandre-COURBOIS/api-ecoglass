<?php

namespace App\Controller;

use App\Service\Register;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/register", name="register_"
 * Class RegisterController
 * @package App\Controller
 */
class RegisterController extends AbstractController
{

    private $registerService;

    public function __construct(Register $register)
    {
        $this->registerService = $register;
    }

    /**
     * @Route("/user", name="user")
     */
    public function userRegister()
    {
        return $this->render('register/index.html.twig', [
            'controller_name' => 'RegisterController',
        ]);
    }
}
