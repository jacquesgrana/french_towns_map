<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\ThemeApiFT;
use App\Service\FranceTravailApiService;

#[AsCommand(
    name: 'app:populate-theme-from-api',
    description: 'Populate table theme_api_ft from api'
    )]
class PopulateThemeFromApiCommand extends Command
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

        $datas = $this->apiFranceTravailService->getThemes();
        $this->em->getConnection()->beginTransaction();
        foreach ($datas as $row) {
            $newTheme = new ThemeApiFT();
            $newTheme->setCode($row['code']);
            $newTheme->setLibelle($row['libelle']);
            $io->text('Row : ' . $newTheme->getCode() . ' - ' .    $newTheme->getLibelle());
            $this->em->persist($newTheme);
        }
        $this->em->flush();
        $this->em->getConnection()->commit();

        $io->success('Table theme_api_ft populated from api');
        return Command::SUCCESS;
    }

}