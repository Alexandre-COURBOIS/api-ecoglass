<?php


namespace App\Command;

use App\Controller\ContainerController;
use App\Repository\ContainersRepository;
use App\Service\Container;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class UpdateContainers extends Command
{
    protected static string $commandName = 'app:update-containers';
    private Container $container;
    private ContainerController $containerController;
    private EntityManagerInterface $manager;
    private ContainersRepository $containerRepo;

    public function __construct(Container $container, ContainerController $containerController, EntityManagerInterface $manager, ContainersRepository $containersRepository)
    {
        $this->container = $container;
        $this->containerController = $containerController;
        $this->manager = $manager;
        $this->containerRepo = $containersRepository;
        
        parent::__construct();
    }
    
    protected function configure()
    {
        $this
            ->setName(self::$commandName)
            ->setDescription('Update containers')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $response = $this->containerController->setGlassContainer();

            $output->writeln([
                '============',
                'Containers update',
                '============',
                $response
            ]);

            return Command::SUCCESS;

        } catch (ClientExceptionInterface | DecodingExceptionInterface | RedirectionExceptionInterface | ServerExceptionInterface | TransportExceptionInterface $e) {
            return Command::FAILURE;
        }
    }

}