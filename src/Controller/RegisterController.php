<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\UserRegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
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
     * @return JsonResponse
     *
     * Methode permettant l'inscription de nouveaux utilisateurs
     */
    public function userRegister(Request $request, ValidatorInterface $validator, EntityManagerInterface $em, UserPasswordEncoderInterface $passwordEncoder): JsonResponse
    {
        $user = new Users();

        $data = json_decode($request->getContent(), true);

        $form = $this->createForm(UserRegisterType::class, $user);

        $form->submit($data);

        $validate = $validator->validate($user,null,'Register');

        if(count($validate) !== 0) {
            foreach ($validate as $error) {
                return new JsonResponse($error->getMessage(), Response::HTTP_BAD_REQUEST);
            }
        }

        $password = $passwordEncoder->encodePassword($user, $user->getPassword());
        $user->setPassword($password);

        $em->persist($user);
        $em->flush();

        return new JsonResponse('User has been created successfully', 200);
    }

}
