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
    private EntityManagerInterface $manager;
    private ContainersRepository $containersRepository;


    public function __construct(Container $container, SerializerService $serializerService, EntityManagerInterface $entityManagerInterface, ContainersRepository $containersRepository)
    {
        $this->containerService = $container;
        $this->serializer = $serializerService;
        $this->manager = $entityManagerInterface;
        $this->containersRepository = $containersRepository;
    }

    /**
     * @Route("/set/glass", name="set_glass", methods={"PUT"})
     * @return JsonResponse
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function setGlassContainer(): JsonResponse
    {
        $glassContainer = $this->containerService->getGlassContainerApi();
        $glassContainerToulouse = $this->containerService->getGlassContainerApiToulouse();
        $glassContainerPoitiers = $this->containerService->getGlassContainerApiPoitiers();

        $containerFeature = $glassContainer["features"];
        $containerRecords = $glassContainerToulouse['records'];
        $containerRecordsPoitier = $glassContainerPoitiers['records'];

        $count = count($containerFeature) + count($containerRecords) + count($containerRecordsPoitier);

        if ($count !== (count($this->containersRepository->findAll()))) {

            $this->containersRepository->deleteAllContainers();

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

                $this->manager->persist($containers);

            }

            for ($i = 0; $i <= count($containerRecords) - 1; $i++) {

                $containersToulouse = new Containers();

                $containerToulouse = $containerRecords[$i];

                $containerIdToulouse = $containerToulouse['fields']['code_insee'];
                $containerCityToulouse = $containerToulouse['fields']['commune'];

                if (array_key_exists('voie', $containerToulouse['fields'])) {
                    $containerStreetToulouse = $containerToulouse['fields']['voie'];
                } else {
                    $containerStreetToulouse = $containerToulouse['fields']['adresse'];
                }

                $containerLongitudeToulouse = $containerToulouse['fields']['geo_shape']['coordinates'][0][0];
                $containerLatitudeToulouse = $containerToulouse['fields']['geo_shape']['coordinates'][0][1];

                $containersToulouse->setContainerId($containerIdToulouse);
                $containersToulouse->setCity($containerCityToulouse);
                $containersToulouse->setStreet($containerStreetToulouse);
                $containersToulouse->setLongitude($containerLongitudeToulouse);
                $containersToulouse->setLatitude($containerLatitudeToulouse);
                $containersToulouse->setCoordinates('POINT(' . $containerLongitudeToulouse . ' ' . $containerLatitudeToulouse . ')');


                $this->manager->persist($containersToulouse);
            }

            for ($i = 0; $i <= count($containerRecordsPoitier) - 1; $i++) {

                $containersPoitier = new Containers();

                $containerPoitier = $containerRecordsPoitier[$i];

                $containerIdPoitier = $containerPoitier['fields']['objectid'];

                if (array_key_exists('commune', $containerPoitier['fields'])) {
                    $containerCityPoitier = $containerPoitier['fields']['commune'];
                } else {
                    $containerCityPoitier = null;
                }

                if (array_key_exists('adresse', $containerPoitier['fields'])) {
                    $containerStreetPoitier = $containerPoitier['fields']['adresse'];
                } else {
                    $containerStreetPoitier = null;
                }

                $containerLongitudePoitier = $containerPoitier['fields']['geo_shape']['coordinates'][0];
                $containerLatitudePoitier = $containerPoitier['fields']['geo_shape']['coordinates'][1];

                $containersPoitier->setContainerId($containerIdPoitier);
                $containersPoitier->setCity($containerCityPoitier);
                $containersPoitier->setStreet($containerStreetPoitier);
                $containersPoitier->setLongitude($containerLongitudePoitier);
                $containersPoitier->setLatitude($containerLatitudePoitier);
                $containersPoitier->setCoordinates('POINT(' . $containerLongitudePoitier . ' ' . $containerLatitudePoitier . ')');


                $this->manager->persist($containersPoitier);
            }

            $this->manager->flush();

            return new JsonResponse("Container data is now updated", Response::HTTP_OK);

        } else {

            return new JsonResponse("Data is already up to date", Response::HTTP_NOT_ACCEPTABLE);

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
     * @Route("/get/api/glass/toulouse", name="get_glass_api_toulouse", methods={"GET"})
     */
    public function getGlassContainerApiToulouse(): Response
    {
        $containers = $this->containerService->getGlassContainerApiPoitiers();

        return new JsonResponse($containers['records'][0]['fields']['geo_shape']['coordinates'][0], Response::HTTP_OK);
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
