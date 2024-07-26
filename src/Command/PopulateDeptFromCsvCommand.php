<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\RegionRepository;
use App\Entity\Region;
use App\Entity\Departement;

#[AsCommand(
    name: 'app:populate-table-dept',
    description: 'Populate Departement table from CSV file',
)]
class PopulateDeptFromCsvCommand extends Command
{

    public function __construct(
        private EntityManagerInterface $em,
        private RegionRepository $regionRepository
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

            if (!file_exists($filePath) || !is_readable($filePath)) {
                $io->error('File not found or not readable: ' . $filePath);
                return Command::FAILURE;
            }

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

            // Traitement des données
            foreach ($data as $row) {
                $deptCode = $row[0];
                $deptRegCode = $row[1];
                $deptName = $row[5];

                $region = $this->regionRepository->findOneBy(['regCode' => $deptRegCode]);
                if(!$region) {
                    $io->error('Region not found: ' . $deptRegCode);
                    return Command::FAILURE;
                }

                $newDept = new Departement();
                $newDept->setDepCode($deptCode);
                $newDept->setDepName($deptName);
                $newDept->setRegion($region);
                $region->addDepartement($newDept);
                $this->em->persist($region);
                $this->em->persist($newDept);

                // afficher chaque ligne
                $io->text($newDept->getDepCode() . ' - ' . $newDept->getDepName() . ' - ' . $newDept->getRegion()->getRegName());
            }

            $this->em->flush();
            $io->success('CSV file imported successfully');
            return Command::SUCCESS;
        }

        /*
        // ouverture du fichier csv /csv/v_region_2023.csv
        try {
            $file = fopen('./Command/csv/v_region_2023.csv', 'r');

            // si fichier ok
            if($file) {
                // boucle sur le fichier csv
                while(!feof($file)) {
                    $line = fgetcsv($file);
                    // extraction des données sélectionnées (codeRegion, nameRegion) de ligne en cours
                    //$dataTab = explode(',', $line);
                    // creation objet Region
                    // insertion dans la table Region
                    // affichage des informations de l'insertion
                    $output->writeln(implode(',', $line));
                }
                    
    
                // $em->flush();
            }
            else {
                $output->writeln('File not found!');
                return Command::FAILURE;
            }
    
                
    
            $output->writeln('Table \'Region\' populated successfully!');
    
            return Command::SUCCESS;
        }
        catch (\Exception $e) {
            $output->writeln($e->getMessage());
            return Command::ERROR;
        }*/
        
    
}
