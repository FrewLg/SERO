<?php

namespace App\Repository\SERO;

use App\Entity\SERO\ReviewersPool;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ReviewersPool>
 *
 * @method ReviewersPool|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReviewersPool|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReviewersPool[]    findAll()
 * @method ReviewersPool[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReviewersPoolRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReviewersPool::class);
    }

    //    /**
    //     * @return ReviewersPool[] Returns an array of ReviewersPool objects
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

    //    public function findOneBySomeField($value): ?ReviewersPool
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
