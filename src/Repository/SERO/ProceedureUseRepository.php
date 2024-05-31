<?php

namespace App\Repository\SERO;

use App\Entity\SERO\ProceedureUse;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProceedureUse>
 *
 * @method ProceedureUse|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProceedureUse|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProceedureUse[]    findAll()
 * @method ProceedureUse[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProceedureUseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProceedureUse::class);
    }

    //    /**
    //     * @return ProceedureUse[] Returns an array of ProceedureUse objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?ProceedureUse
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
