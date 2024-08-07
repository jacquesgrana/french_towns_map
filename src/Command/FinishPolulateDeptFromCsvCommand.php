<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\DepartementRepository;
use App\Repository\TownRepository;

/**
 * Commande pour finir de peupler la table departement pour définir les capitales
 * utiliser le fichier csv : src/Command/csv/v_departement_2023.csv
 * pour lancer la commande : php bin/console app:finish-populate-table-dept v_departement_2023.csv
 */
#[AsCommand(
    name: 'app:finish-populate-table-dept',
    description: 'Finish to populate Departement table from CSV file : add capital_town_id',
)]
class FinishPolulateDeptFromCsvCommand extends Command
{

    public function __construct(
        private EntityManagerInterface $em,
        private DepartementRepository $deptRepository,
        private TownRepository $townRepository
        )
    {
        parent::__construct();
    }
   
        // Logique pour peupler la table

        protected function configure(): void
        {
            $this
                ->addArgument('file', InputArgument::REQUIRED, 'The name of the CSV file in src/Command/csv');
        }

        protected function execute(InputInterface $input, OutputInterface $output): int
        {
            $io = new SymfonyStyle($input, $output);
            $fileName = $input->getArgument('file');
            $filePath = __DIR__ . '/csv/' . $fileName;

            $io->text('Open file: ' . $filePath);


            if (!file_exists($filePath) || !is_readable($filePath)) {
                $io->error('File not found or not readable: ' . $filePath);
                return Command::FAILURE;
            }

            $io->text('Load datas from file: ' . $filePath);

            $data = [];
            
            if (($handle = fopen($filePath, 'r')) !== false) {
                $cpt = 0;
                while (($row = fgetcsv($handle, 1000, ',')) !== false) {
                    $cpt++;
                    if($cpt == 1) continue;
                    $data[] = $row;
                }
                fclose($handle);
            }

            $io->text('Use datas from memory: ' . count($data) . ' lines');

            // Traitement des données
            foreach ($data as $row) {
                $deptCode = $row[0];
                $deptCapitalCode = $row[2];

                // récupérer la town à partir de son code
                $town = $this->townRepository->findOneBy(['townCode' => $deptCapitalCode]);
                if(!$town) {
                    $io->error('Town not found: ' . $deptCapitalCode);
                    return Command::FAILURE;
                }
                // récuperer le departement à partir de son code
                $dept = $this->deptRepository->findOneBy(['depCode' => $deptCode]);
                if(!$dept) {
                    $io->error('Departement not found: ' . $deptCode);
                    return Command::FAILURE;
                }
                $dept->setCapitalTown($town);
                $town->setCapitalOfDepartement($dept);
                $this->em->persist($town);
                $this->em->persist($dept);

                $io->text($dept->getDepCode() . ' - ' . $dept->getDepName() . ' - ' . $dept->getRegion()->getRegName() . ' - ' . $town->getTownName());
            }

            $this->em->flush();
            $io->success('CSV file imported successfully');
            return Command::SUCCESS;
        }
}
