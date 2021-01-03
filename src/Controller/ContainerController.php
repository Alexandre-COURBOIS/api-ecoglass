<?php

namespace App\Controller;

use App\Service\Container;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("api/container", name="container_")
 */
class ContainerController extends AbstractController
{

    private $containerService;

    public function __construct(Container $container)
    {
        $this->containerService = $container;
    }

    /**
     * @Route("/set/glass", name="set_glass", methods={"PATCH"})
     * @param EntityManagerInterface $manager
     * @return JsonResponse
     */
    public function setGlassContainer(EntityManagerInterface $manager): JsonResponse
    {
        $this->containerService->setGlassContainer($manager);

        return new JsonResponse("Containers data successfully updated", Response::HTTP_OK);
    }

    /**
     * @Route("/get/glass", name="get_glass", methods={"GET"})
     */
    public function getGlassContainer(): Response
    {
        $container = $this->containerService->getGlassContainer();

        return new JsonResponse($container, 200);
    }
}