<?php

namespace App\Controller;

use App\Repository\UsersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Json;

class ForgotPasswordController extends AbstractController
{
    /**
     * @Route("/forgot/password", name="forgot_password", methods={"POST"})
     * @param Request $request
     * @param UsersRepository $usersRepository
     * @param EntityManagerInterface $em
     * @param MailerInterface $mailer
     * @return JsonResponse
     * @throws \Exception
     * @throws TransportExceptionInterface
     */
    public function sendMailForgotPassword(Request $request, UsersRepository $usersRepository, EntityManagerInterface $em, MailerInterface $mailer): JsonResponse
    {
        $content = json_decode($request->getContent());

        $email = $content->email;

        $user = $usersRepository->findOneBy(['email' => $email]);

        if ($user) {

            $user->setResetToken(rtrim(strtr(base64_encode(random_bytes(64)), '+/', '-_'), '='));
            $date = new \DateTime('now', new \DateTimeZone('Europe/Paris'));
            $user->setResetTokenAt($date);
            $user->setUpdatedAt(new \DateTime());

            $em->persist($user);
            $em->flush();

            $email = (new TemplatedEmail())
                ->from('support.web@ecoglass.com')
                ->to($user->getEmail())
                ->subject('Mot de passe oublié ?')
                ->htmlTemplate('forgot_password/forgot_password.html.twig')
                ->context([
                    'user' => $user,
                ]);

            $mailer->send($email);

            return new JsonResponse('Un email vient de vous êtres envoyé !', Response::HTTP_OK);

        } else {
            return new JsonResponse("Email incorrect, ou innexistant.", RESPONSE::HTTP_BAD_REQUEST);
        }

    }

    /**
     * @Route("/reset/password", name="reset_password", methods={"POST"})
     * @param Request $request
     * @param UsersRepository $usersRepository
     * @param EntityManagerInterface $em
     * @return JsonResponse
     */
    public function resetForgotPassword(Request $request, UsersRepository $usersRepository, EntityManagerInterface $em): JsonResponse
    {
        $content = json_decode($request->getContent());

        $token = $content->token;
        $date = $content->date;

        $user = $usersRepository->findOneBy(['resetToken' => $token]);

        if ($user) {
            if ($user->getResetToken() === $token && $user->getResetToken() && $user->getResetTokenAt()) {

                $formatStartDate = $user->getResetTokenAt();
                $formatCurrentDate = \DateTime::createFromFormat("Y-m-d H:i:s", $date);

                $interval = $formatStartDate->diff($formatCurrentDate);

                if ($interval->d === 0 && $interval->m === 0 && $interval->h === 0) {
                    if ((int)$interval->i < 30) {
                        return new JsonResponse( Response::HTTP_OK);
                    } else {
                        $user->setResetToken(null);
                        $user->setResetTokenAt(null);
                        $em->persist($user);
                        $em->flush();
                        return new JsonResponse("Le délais pour changer votre mot de passe à expirer, merci de renouveler la demande.", RESPONSE::HTTP_BAD_REQUEST);
                    }
                } else {
                    return new JsonResponse("Le délais pour changer votre mot de passe à expirer, merci de renouveler la demande.", RESPONSE::HTTP_BAD_REQUEST);
                }
            } else {
                return new JsonResponse("Cette url n'existe pas ou est incorrect.", RESPONSE::HTTP_BAD_REQUEST);
            }
        } else {
            return new JsonResponse("Cette url n'existe pas ou est incorrect.", RESPONSE::HTTP_BAD_REQUEST);
        }
    }

}
