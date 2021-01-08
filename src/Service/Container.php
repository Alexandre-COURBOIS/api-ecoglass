<?php


namespace App\Service;

use App\Entity\Containers;
use App\Repository\ContainersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class Container
{

    /**
     * @return object
     */
    public function getGlassContainerApi(): object
    {
        $json = file_get_contents("https://www.data.gouv.fr/fr/datasets/r/85ad9858-0f57-4ae0-9af4-e90165ee83ae");
        $container = json_decode($json);

        return $container;
    }

    /**
     * @return object
     */
    public function getGlassContainerDatabase(): object
    {
        $json = file_get_contents("https://www.data.gouv.fr/fr/datasets/r/85ad9858-0f57-4ae0-9af4-e90165ee83ae");
        $glassContainer = json_decode($json);

        return $glassContainer;
    }

}