<?php

namespace App\Controller;

use App\Repository\UsersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Json;


/**
 * @Route("/api/user", name="user_")
 * Class RegisterController
 * @package App\Controller
 */
class UserController extends AbstractController
{
    /**
     * @Route("/get/user", name="get_user", methods={"POST"})
     * @param UsersRepository $usersRepository
     * @param Request $request
     * @return JsonResponse
     */
    public function getUserInformations(UsersRepository $usersRepository, Request $request): JsonResponse
    {
        $datas = json_decode($request->getContent(), true);

        if ($datas !== null && $datas) {

            $email = $datas['email'];

            $user = $usersRepository->getUserByEmail($email);

            return new JsonResponse($user, Response::HTTP_OK);

        } else {
            return new JsonResponse("Please send a valid data", Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @Route("/update/user", name="update_user", methods={"POST"})
     * @param UsersRepository $usersRepository
     * @param Request $request
     * @return JsonResponse
     */
    public function updateUserPersonnalInformations(UsersRepository $usersRepository, Request $request): JsonResponse
    {
        $datas = json_decode($request->getContent(), true);

        if ($datas !== null && $datas) {

            $user = $this->getUser();

            return new JsonResponse($user, Response::HTTP_OK);

        } else {
            return new JsonResponse("Please send a valid data", Response::HTTP_BAD_REQUEST);
        }
    }


}
