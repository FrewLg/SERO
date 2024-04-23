<?php

namespace App\Repository\SERO;

use App\Entity\SERO\Continuation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Continuation>
 *
 * @method Continuation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Continuation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Continuation[]    findAll()
 * @method Continuation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContinuationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Continuation::class);
    }

    //    /**
    //     * @return Continuation[] Returns an array of Continuation objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Continuation
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
