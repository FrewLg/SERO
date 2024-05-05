<?php

namespace App\Repository\SERO;

use App\Entity\SERO\ReviewType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ReviewType>
 *
 * @method ReviewType|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReviewType|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReviewType[]    findAll()
 * @method ReviewType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReviewTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReviewType::class);
    }

    //    /**
    //     * @return ReviewType[] Returns an array of ReviewType objects
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

    //    public function findOneBySomeField($value): ?ReviewType
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
