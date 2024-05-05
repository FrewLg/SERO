<?php

namespace App\Repository\SERO;

use App\Entity\SERO\Amendment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Amendment>
 *
 * @method Amendment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Amendment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Amendment[]    findAll()
 * @method Amendment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AmendmentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Amendment::class);
    }

    //    /**
    //     * @return Amendment[] Returns an array of Amendment objects
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

    //    public function findOneBySomeField($value): ?Amendment
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
