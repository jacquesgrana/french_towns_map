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
use App\Repository\PositionGpsRepository;
use App\Repository\TownRepository;
use App\Entity\PositionGps;
use App\Entity\Town;
use App\Entity\Departement;

#[AsCommand(
    name: 'app:populate-table-town',
    description: 'Populate Town table from CSV file',
)]
class PopulateTownFromCsvCommand extends Command
{

    public function __construct(
        private EntityManagerInterface $em,
        private DepartementRepository $deptRepository,
        private PositionGpsRepository $positionGpsRepository,
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
        $cptAll = 0;
        $cptSome = 0;
        $towns = [];
        foreach ($data as $row) {
            $cptAll++;
            //$townCode = $row[0];
            $townCode = str_pad($row[0], 5, '0', STR_PAD_LEFT);
            // ajouter '0' au début si < 5 caractères
            $townZipCode = str_pad($row[2], 5, '0', STR_PAD_LEFT);
            //$townZipCode $row[2];
            $townLatitude = $row[5];
            $townLongitude = $row[6];
            $townName = $row[10];
            $townDeptCode = str_pad($row[11], 2, '0', STR_PAD_LEFT);

            $dept = $this->deptRepository->findOneBy(['depCode' => $townDeptCode]);
            if(!$dept) {
                $io->error('Departement not found: ' . $townDeptCode);
                //return Command::FAILURE;
            }
            else if($townLatitude != '' && $townLongitude != '') {

                // chercher si une town a deja ce townCode
                if (!array_filter($towns, function($town) use ($townCode) {
                    return $town->getTownCode() == $townCode;
                })) {
                    $cptSome++;
                    $newTown = new Town();
                    $newTown->setTownCode($townCode);
                    $newTown->setTownZipCode($townZipCode);
                    $newTown->setTownName($townName);
                    $newTown->setDepartement($dept);     
                    $dept->addTown($newTown);
                    $positionGps = new PositionGps();
                    // chercher si la position existe deja
                    $position = $this->positionGpsRepository->findOneBy(['latitude' => $townLatitude, 'longitude' => $townLongitude]);
                    if($position) {
                        $positionGps = $position;
                    }
                    else {
                        $positionGps->setLatitude($townLatitude);
                        $positionGps->setLongitude($townLongitude);
                        $positionGps->addTown($newTown);
                    }
                    
                    $newTown->setPositionGps($positionGps);
                    $towns[] = $newTown;
                    $this->em->persist($dept);
                    $this->em->persist($positionGps);
                    $this->em->persist($newTown);

                    // afficher chaque ligne
                    $io->text($newTown->getTownCode() . ' - ' . $newTown->getTownName() . ' - ' . $dept->getDepName() . ' - ' . $newTown->getPositionGps()->getLatitude() . ' - ' . $newTown->getPositionGps()->getLongitude()); 
                }
                else {
                    $io->text('Town already exists: ' . $townCode);
                }


            }

            
        }
        

        $this->em->flush();
        $io->success('CSV file imported successfully, towns added: ' . $cptSome . ' - total read towns: ' . $cptAll);
        return Command::SUCCESS;
    }     
    
}
