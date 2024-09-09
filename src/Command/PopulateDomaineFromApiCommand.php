<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\DomaineApiFT;
use App\Service\FranceTravailApiService;

#[AsCommand(
    name: 'app:populate-domaine-from-api',
    description: 'Populate type contrat table domaine_api_ft from api'
    )]
class PopulateDomaineFromApiCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $em,
        private FranceTravailApiService $apiFranceTravailService
        )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $datas = $this->apiFranceTravailService->getDomaines();
        $this->em->getConnection()->beginTransaction();
        foreach ($datas as $row) {
            $newDomaine = new DomaineApiFT();
            $newDomaine->setCode($row['code']);
            $newDomaine->setLibelle($row['libelle']);
            $io->text('Row : ' . $newDomaine->getCode() . ' - ' . $newDomaine->getLibelle());
            $this->em->persist($newDomaine);
        }
        $this->em->flush();
        $this->em->getConnection()->commit();

        $io->success('Table domaine_api_ft populated from api');
        return Command::SUCCESS;
    }
}