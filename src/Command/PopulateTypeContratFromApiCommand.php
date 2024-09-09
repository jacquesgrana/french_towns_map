<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\TypeContratApiFT;
use App\Service\FranceTravailApiService;

#[AsCommand(
    name: 'app:populate-type-contrat-from-api',
    description: 'Populate type contrat table type_contrat_api_ft from api'
    )]
class PopulateTypeContratFromApiCommand extends Command
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

        $datas = $this->apiFranceTravailService->getTypesContrats();
        $this->em->getConnection()->beginTransaction();
        foreach ($datas as $row) {
            $newTypeContrat = new TypeContratApiFT();
            $newTypeContrat->setCode($row['code']);
            $newTypeContrat->setLibelle($row['libelle']);
            $io->text('Row : ' . $newTypeContrat->getCode() . ' - ' . $newTypeContrat->getLibelle());
            $this->em->persist($newTypeContrat);
        }
        $this->em->flush();
        $this->em->getConnection()->commit();

        $io->success('Table type_contrat_api_ft populated from api');
        return Command::SUCCESS;
    }

}