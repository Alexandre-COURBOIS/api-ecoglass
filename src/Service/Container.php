<?php


namespace App\Service;

use App\Entity\Containers;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class Container
{

    /**
     * @param EntityManagerInterface $manager
     * @return object
     */
    public function setGlassContainer(EntityManagerInterface $manager)
    {
        $json = file_get_contents("https://www.data.gouv.fr/fr/datasets/r/85ad9858-0f57-4ae0-9af4-e90165ee83ae");
        $glassContainer = json_decode($json);

        $ContainerFeature = $glassContainer->features;

        for ($i=0; $i <= count($ContainerFeature) -1; $i++) {

            $containers = new Containers();

            $container = $ContainerFeature[$i];

            $containerId = $container->properties->identifiant;
            $containerCity = $container->properties->commune;
            $containerPostalCode = $container->properties->code_postal;
            $containerStreet = $container->properties->voie;
            $containerLongitude = $container->geometry->coordinates[0];
            $containerLatitude = $container->geometry->coordinates[1];

            $containers->setContainerId((int)$containerId);
            $containers->setCity($containerCity);
            $containers->setPostalCode($containerPostalCode);
            $containers->setStreet($containerStreet);
            $containers->setLongitude($containerLongitude);
            $containers->setLatitude($containerLatitude);

            $manager->persist($containers);

        }

        $manager->flush();

        return new Response("Service accepted request", Response::HTTP_OK);
    }

    /**
     * @return object
     */
    public function getGlassContainer() : object
    {
        $json = file_get_contents("https://www.data.gouv.fr/fr/datasets/r/85ad9858-0f57-4ae0-9af4-e90165ee83ae");
        $container = json_decode($json);

        return $container;
    }
}