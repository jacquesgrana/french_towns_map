<?php

namespace App\Repository;

use App\Entity\AccessTokenApiFT;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AccessTokenApiFT>
 */
class AccessTokenApiFTRepository extends ServiceEntityRepository
{
    public function __construct(
        private EntityManagerInterface $em, 
        ManagerRegistry $registry
        )
    {
        $this->em = $em;
        parent::__construct($registry, AccessTokenApiFT::class);
    }

    public function findLastValidTokenAndCleanTable(): ?AccessTokenApiFT{
        $tokens = $this->findAll();
        
        if(count($tokens) == 0) {
            return null;
        }
        $validTokens = [];
        $time = time();
        foreach ($tokens as $token) {
            if ($token->getValidUntilTS() < $time) {
                $this->em->remove($token);
                $this->em->flush();
            }
            else {
                $validTokens[] = $token;
            }
        }

        if(count($validTokens) == 0) {
            return null;
        }

        // chercher le dernier token valide qui a le timsestamp le plus élevé et supprimer tous les autres
        if (count($validTokens) > 1) {
           
            usort($validTokens, function($a, $b) {
                return $a->getValidUntilTS() <=> $b->getValidUntilTS();
            });
            $count = 0;
            foreach ($validTokens as $token) {
                $count++;
                if($count > 1) {
                    $this->em->remove($token);
                    $this->em->flush();
                }
            }

            return $validTokens[0];
        }
        else {
            return $validTokens[0];  
        }
    }

    //    /**
    //     * @return AccessTokenApiFT[] Returns an array of AccessTokenApiFT objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('a.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?AccessTokenApiFT
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
