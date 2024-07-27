<?php

namespace App\Repository;

use App\Entity\Town;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @extends ServiceEntityRepository<Town>
 */
class TownRepository extends ServiceEntityRepository
{
    private $em;
    public function __construct(
        ManagerRegistry $registry,
        EntityManagerInterface $em
        )
    {
        parent::__construct($registry, Town::class);
        $this->em = $em;
    }

    //    /**
    //     * @return Town[] Returns an array of Town objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('t.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Town
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function findByBoundingBox(float $swLat, float $swLng, float $neLat, float $neLng): array
    {
        $query = $this->em->createQuery(
            'SELECT 
            t.id, 
            t.townCode, 
            t.townZipCode, 
            t.townName, 
            d.depName, 
            r.regName, 
            p.latitude, 
            p.longitude 
            FROM App\Entity\Town t
            JOIN t.positionGps p
            JOIN t.departement d
            JOIN d.region r
            WHERE p.latitude >= :swLat 
            AND p.longitude >= :swLng
            AND p.latitude <= :neLat 
            AND p.longitude <= :neLng'
        );

        $query->setParameters([
            'swLat' => $swLat,
            'swLng' => $swLng,
            'neLat' => $neLat,
            'neLng' => $neLng,
        ]);
        return $query->getResult();
    }

    public function findByName(string $nameToFind): array
    {
        $query = $this->em->createQuery(
            'SELECT 
            t.id, 
            t.townCode, 
            t.townZipCode, 
            t.townName, 
            d.depName, 
            r.regName, 
            p.latitude, 
            p.longitude 
            FROM App\Entity\Town t
            JOIN t.positionGps p
            JOIN t.departement d
            JOIN d.region r
            WHERE UPPER(t.townName) LIKE :nameToFind'
        );
    
        $query->setParameter('nameToFind', '%' . $nameToFind . '%');

        return $query->getResult();
    }
}
