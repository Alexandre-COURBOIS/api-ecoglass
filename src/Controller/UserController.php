<?php

namespace App\Controller;

use App\Form\UserContactInformationsType;
use App\Form\UserPasswordType;
use App\Form\UserPersonnalInformationsType;
use App\Repository\UsersRepository;
use App\Service\SecurityFunctions;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Validator\Validator\ValidatorInterface;


/**
 * @Route("/api/user", name="user_")
 * Class RegisterController
 * @package App\Controller
 */
class UserController extends AbstractController
{
    private SecurityFunctions $sf;
    private Serializer $serializer;

    public function __construct(SecurityFunctions $securityFunctions)
    {
        $this->sf = $securityFunctions;

        $encoders = [new XmlEncoder(), new JsonEncoder()];

        $normalizers = [new ObjectNormalizer()];

        $this->serializer= new Serializer($normalizers, $encoders);
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

            $user = $usersRepository->findOneBy(['email' => $email]);

            $jsonContent = $this->serializer->serialize($user, 'json');

            return JsonResponse::fromJsonString($jsonContent);

        } else {
            return new JsonResponse("Please send a valid data", Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @Route("/update/personnal/informations", name="update_personnal_informations_user", methods={"POST"})
     * @param Request $request
     * @param ValidatorInterface $validator
     * @param EntityManagerInterface $em
     * @param MailerInterface $mailer
     * @return JsonResponse
     * @throws TransportExceptionInterface
     */
    public function updateUserPersonnalInformations(Request $request, ValidatorInterface $validator, EntityManagerInterface $em, MailerInterface $mailer): JsonResponse
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

                $email = (new TemplatedEmail())
                    ->from('support.web@ecoglass.com')
                    ->to($user->getEmail())
                    ->subject('Mise à jour de vos informations')
                    ->htmlTemplate('email/informations_update.html.twig')
                    ->context([
                        'user' => $user,
                    ]);

                $mailer->send($email);

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
     * @param EntityManagerInterface $em
     * @param MailerInterface $mailer
     * @return JsonResponse
     */
    public function updateUserContactInformations(Request $request, ValidatorInterface $validator, EntityManagerInterface $em, MailerInterface $mailer): JsonResponse
    {
        $datas = json_decode($request->getContent(), true);

        if ($datas !== null && $datas) {

            $user = $this->getUser();

            if ($user) {

                $datas['email'] ? true : $datas['email'] = $user->getEmail();
                $datas['address'] ? true : $datas['address'] = $user->getAddress();
                $datas['city'] ? true : $datas['city'] = $user->getCity();
                $datas['postalCode'] ? true : $datas['postalCode'] = $user->getPostalCode();

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

    /**
     * @Route("/update/password", name="update_password_user", methods={"POST"})
     * @param Request $request
     * @param ValidatorInterface $validator
     * @param EntityManagerInterface $em
     * @param UserPasswordEncoderInterface $encoder
     * @param MailerInterface $mailer
     * @return JsonResponse
     */
    public function updateUserPassword(Request $request, ValidatorInterface $validator, EntityManagerInterface $em, UserPasswordEncoderInterface $encoder, MailerInterface $mailer): JsonResponse
    {
        $datas = json_decode($request->getContent(), true);

        if ($datas !== null && $datas) {

            $user = $this->getUser();

            if ($user) {

                $oldPassword = $datas['oldPassword'];

                $newPassword = $datas['password'];

                $verifNewPassword = $datas['verifPassword'];

                if ($newPassword === $verifNewPassword) {

                    if (password_verify($oldPassword, $user->getPassword())) {

                        $form = $this->createForm(UserPasswordType::class, $user);

                        $form->submit($datas);

                        $validate = $validator->validate($user, null, 'PasswordUpdate');

                        if (count($validate) !== 0) {
                            foreach ($validate as $error) {
                                return new JsonResponse($error->getMessage(), Response::HTTP_BAD_REQUEST);
                            }
                        }

                        $hashword = $encoder->encodePassword($user, $user->getPassword());

                        $user->setPassword($hashword);
                        $user->setUpdatedAt(new \DateTime());

                        $em->persist($user);
                        $em->flush();

                        $email = (new TemplatedEmail())
                            ->from('support.web@ecoglass.com')
                            ->to($user->getEmail())
                            ->subject('Mise à jour de vos informations')
                            ->htmlTemplate('email/informations_update.html.twig')
                            ->context([
                                'user' => $user,
                            ]);

                        $mailer->send($email);

                        return new JsonResponse('Le mot de passe a bien été mis à jour !', Response::HTTP_OK);

                    } else {
                        return new JsonResponse("Le mot de actuel renseigné n'est pas valide !", Response::HTTP_BAD_REQUEST);
                    }
                } else {
                    return new JsonResponse("Les deux nouveaux mot de passe ne correspondent pas !", Response::HTTP_BAD_REQUEST);
                }
            } else {
                return new JsonResponse("Veuillez vous connecter !", Response::HTTP_BAD_REQUEST);
            }
        } else {
            return new JsonResponse("Merci de renseigner des informations !", Response::HTTP_BAD_REQUEST);
        }
    }


}
