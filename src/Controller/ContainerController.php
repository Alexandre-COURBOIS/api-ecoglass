<?php

namespace App\Controller;

use App\Entity\Containers;
use App\Repository\ContainersRepository;
use App\Service\Container;
use App\Service\SerializerService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

/**
 * @Route("api/container", name="container_")
 */
class ContainerController extends AbstractController
{

    private Container $containerService;
    private SerializerService $serializer;


    public function __construct(Container $container, SerializerService $serializerService)
    {
        $this->containerService = $container;
        $this->serializer = $serializerService;
    }

    /**
     * @Route("/set/glass", name="set_glass", methods={"PUT"})
     * @param EntityManagerInterface $manager
     * @param ContainersRepository $containersRepository
     * @return JsonResponse
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function setGlassContainer(EntityManagerInterface $manager, ContainersRepository $containersRepository): JsonResponse
    {
        $glassContainer = $this->containerService->getGlassContainerApi();

        $containerFeature = $glassContainer["features"];

        if (count($containerFeature) !== (count($containersRepository->findAll()))) {

            $containersRepository->deleteAllContainers();

            for ($i = 0; $i <= count($containerFeature) - 1; $i++) {

                $containers = new Containers();

                $container = $containerFeature[$i];

                $containerId = $container["properties"]["identifiant"];
                $containerCity = $container["properties"]["commune"];
                $containerPostalCode = $container["properties"]["code_postal"];
                $containerStreet = $container["properties"]["voie"];
                $containerLongitude = $container["geometry"]["coordinates"][0];
                $containerLatitude = $container["geometry"]["coordinates"][1];

                $containers->setContainerId((int)$containerId);
                $containers->setCity($containerCity);
                $containers->setPostalCode($containerPostalCode);
                $containers->setStreet($containerStreet);
                $containers->setLongitude($containerLongitude);
                $containers->setLatitude($containerLatitude);
                $containers->setCoordinates('POINT(' . $containerLongitude . ' ' . $containerLatitude . ')');

                $manager->persist($containers);

            }

            $manager->flush();

            return new JsonResponse("Container data is now updated", Response::HTTP_OK);

        } else {

            return new JsonResponse("Data is already updated", Response::HTTP_NOT_ACCEPTABLE);

        }
    }

    /**
     * @Route("/get/api/glass", name="get_glass_api", methods={"GET"})
     */
    public function getGlassContainerApi(): Response
    {
        $containers = $this->containerService->getGlassContainerApi();

        return new JsonResponse($containers["features"], Response::HTTP_OK);
    }

    /**
     * @Route("/get/database/glass", name="get_glass_database", methods={"GET"})
     * @param ContainersRepository $containersRepository
     * @return Response
     */
    public function getGlassContainerDatabase(ContainersRepository $containersRepository): Response
    {
        return JsonResponse::fromJsonString($this->serializer->SimpleSerializer($containersRepository->findAll(), 'json'));
    }

}
