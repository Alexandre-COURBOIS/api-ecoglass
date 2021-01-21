<?php

namespace App\Controller;

use App\Form\UserContactInformationsType;
use App\Form\UserPersonnalInformationsType;
use App\Repository\UsersRepository;
use App\Service\SecurityFunctions;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Json;
use Symfony\Component\Validator\Validator\ValidatorInterface;


/**
 * @Route("/api/user", name="user_")
 * Class RegisterController
 * @package App\Controller
 */
class UserController extends AbstractController
{

    private SecurityFunctions $sf;

    public function __construct(SecurityFunctions $securityFunctions)
    {
        $this->sf = $securityFunctions;
    }

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
     * @Route("/update/personnal/informations", name="update_personnal_informations_user", methods={"POST"})
     * @param Request $request
     * @param ValidatorInterface $validator
     * @return JsonResponse
     */
    public function updateUserPersonnalInformations(Request $request, ValidatorInterface $validator, EntityManagerInterface $em): JsonResponse
    {
        $datas = json_decode($request->getContent(), true);

        if ($datas !== null && $datas) {

            $user = $this->getUser();

            if ($user) {

                $datas['name'] ? true : $datas['name'] = $user->getName();
                $datas['surname'] ? true : $datas['surname'] = $user->getSurname();
                $datas['pseudo'] ? true : $datas['pseudo'] = $user->getPseudo();

                $form = $this->createForm(UserPersonnalInformationsType::class, $user);

                $form->submit($datas);

                $validate = $validator->validate($user, null, 'UpdatePersonnalInformations');

                if (count($validate) !== 0) {
                    foreach ($validate as $error) {
                        return new JsonResponse($error->getMessage(), Response::HTTP_BAD_REQUEST);
                    }
                }

                $user->setUpdatedAt(new \DateTime());

                $em->persist($user);
                $em->flush();

                return new JsonResponse('Les informations ont bien été mise à jour !', Response::HTTP_OK);

            } else {
                return new JsonResponse("Veuillez vous connecter !", Response::HTTP_BAD_REQUEST);
            }
        } else {
            return new JsonResponse("Merci de renseigner des informations !", Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @Route("/update/contact/informations", name="update_contact_informations_user", methods={"POST"})
     * @param Request $request
     * @param ValidatorInterface $validator
     * @return JsonResponse
     */
    public function updateUserContactInformations(Request $request, ValidatorInterface $validator, EntityManagerInterface $em): JsonResponse
    {
        $datas = json_decode($request->getContent(), true);

        if ($datas !== null && $datas) {

            $user = $this->getUser();

            if ($user) {

                $datas['email'] ? true : $datas['email'] = $user->getName();
                $datas['address'] ? true : $datas['address'] = $user->getSurname();
                $datas['city'] ? true : $datas['city'] = $user->getPseudo();
                $datas['postalCode'] ? true : $datas['postalCode'] = $user->getPseudo();

                $form = $this->createForm(UserContactInformationsType::class, $user);

                $form->submit($datas);

                $validate = $validator->validate($user, null, 'UpdateContactInformations');

                if (count($validate) !== 0) {
                    foreach ($validate as $error) {
                        return new JsonResponse($error->getMessage(), Response::HTTP_BAD_REQUEST);
                    }
                }

                $user->setUpdatedAt(new \DateTime());

                $em->persist($user);
                $em->flush();

                return new JsonResponse('Les informations ont bien été mise à jour !', Response::HTTP_OK);

            } else {
                return new JsonResponse("Veuillez vous connecter !", Response::HTTP_BAD_REQUEST);
            }
        } else {
            return new JsonResponse("Merci de renseigner des informations !", Response::HTTP_BAD_REQUEST);
        }
    }


}
