<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\CodeNafApiFT;
use App\Service\FranceTravailApiService;

#[AsCommand(
    name: 'app:populate-code-naf-from-api', 
    description: 'Populate table code_naf_api_ft from api',
    )]
class PopulateCodeNafFromApiCommand extends Command
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
        $codesNaf = $this->apiFranceTravailService->getCodesNaf();
        $this->em->getConnection()->beginTransaction();
        foreach ($codesNaf as $codeNaf) {
            $codeNafEntity = new CodeNafApiFT();
            $codeNafEntity->setCode($codeNaf['code']);
            $codeNafEntity->setLibelle($codeNaf['libelle']);
            $io->text('Row : ' . $codeNafEntity->getCode() . ' - ' . $codeNafEntity->getLibelle());
            $this->em->persist($codeNafEntity);
        }
        $this->em->flush();
        $this->em->getConnection()->commit();
        $io->success('Table code_naf_api_ft populated from api');
        return Command::SUCCESS;
    }
}