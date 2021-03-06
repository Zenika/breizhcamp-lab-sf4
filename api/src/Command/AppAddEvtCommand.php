<?php

namespace App\Command;

use App\Entity\Evenement;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class AppAddEvtCommand extends Command
{
    protected static $defaultName = 'app:add-evt';
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
    }

    protected function configure()
    {
        $this
            ->setDescription('Ajoute les évènements');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $evt = new Evenement();
        $evt->setNom('BreizhCamp 2018')->setImage('evenements/breizhcamp-2018.jpg')->setDate(new \DateTime('2018-03-29T10:30:00'));
        $this->entityManager->persist($evt);

        $evt= new Evenement();
        $evt->setNom('1er ordinateur')->setImage('evenements/1er-ordinateur.png')->setDate(new \DateTime('1936-06-14T15:29:18'));
        $this->entityManager->persist($evt);

        $evt= new Evenement();
        $evt->setNom('1ere version de php')->setImage('evenements/php.png')->setDate(new \DateTime('1994-11-25T03:43:25'));
        $this->entityManager->persist($evt);

        $evt = new Evenement();
        $evt->setNom('1ere version de symfony')->setImage('evenements/symfony.png')->setDate(new \DateTime('2005-10-18T12:13:43'));
        $this->entityManager->persist($evt);

        $evt = new Evenement();
        $evt->setNom('BreizhCamp 2019')->setImage('evenements/breizhcamp-2019.png')->setDate(new \DateTime('2019-02-07T13:37:00'));
        $this->entityManager->persist($evt);

        $this->entityManager->flush();

        $io = new SymfonyStyle($input, $output);
        $io->success('5 évènements ajoutés');
    }
}
