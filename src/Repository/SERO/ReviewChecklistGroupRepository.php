<?php

namespace App\Repository\SERO;

use App\Entity\SERO\ReviewChecklistGroup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ReviewChecklistGroup>
 *
 * @method ReviewChecklistGroup|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReviewChecklistGroup|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReviewChecklistGroup[]    findAll()
 * @method ReviewChecklistGroup[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReviewChecklistGroupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReviewChecklistGroup::class);
    }

    //    /**
    //     * @return ReviewChecklistGroup[] Returns an array of ReviewChecklistGroup objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('r.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?ReviewChecklistGroup
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
