<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\MetierRomeApiFT;
use App\Service\FranceTravailApiService;

#[AsCommand(
    name: 'app:populate-metier-rome-from-api',
    description: 'Populate table metier_rome_api_ft from api'
    )]
class PopulateMetierRomeFromApiCommand extends Command
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

        $datas = $this->apiFranceTravailService->getMetiersRome();
        $this->em->getConnection()->beginTransaction();
        foreach ($datas as $row) {
            $newMetierRome = new MetierRomeApiFT();
            $newMetierRome->setCode($row['code']);
            $newMetierRome->setLibelle($row['libelle']);
            $io->text('Row : ' . $newMetierRome->getCode() . ' - ' . $newMetierRome->getLibelle());
            $this->em->persist($newMetierRome);
        }
        $this->em->flush();
        $this->em->getConnection()->commit();

        $io->success('Table metier_rome_api_ft populated from api');
        return Command::SUCCESS;
    }
}