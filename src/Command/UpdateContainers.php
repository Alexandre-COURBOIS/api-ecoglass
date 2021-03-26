<?php


namespace App\Command;

use App\Controller\ContainerController;
use App\Repository\ContainersRepository;
use App\Service\Container;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
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
    private MailerInterface $mailerInterface;

    public function __construct(Container $container, ContainerController $containerController, EntityManagerInterface $manager, ContainersRepository $containersRepository, MailerInterface $mailer)
    {
        $this->container = $container;
        $this->containerController = $containerController;
        $this->manager = $manager;
        $this->containerRepo = $containersRepository;
        $this->mailerInterface = $mailer;
        
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
                '<fg=green>' .$response. '</>'
            ]);

            $email = (new Email())
                ->from('updateWithCron@ecoglass.fr')
                ->to("courbois.alexandre76440@gmail.com")
                ->subject("La tâche cron a bien été executée")
                ->text("la tâche cron c'est bien executée celle-ci a renvoyé le message suivant : ".$response);

            $this->mailerInterface->send($email);

            return Command::SUCCESS;

        } catch (ClientExceptionInterface | DecodingExceptionInterface | RedirectionExceptionInterface | ServerExceptionInterface | TransportExceptionInterface $e) {
            return Command::FAILURE;
        }
    }

}