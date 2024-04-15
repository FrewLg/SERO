<?php

namespace App\Repository;

use App\Entity\SERO\ApplicationReview;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ApplicationReview>
 *
 * @method ApplicationReview|null find($id, $lockMode = null, $lockVersion = null)
 * @method ApplicationReview|null findOneBy(array $criteria, array $orderBy = null)
 * @method ApplicationReview[]    findAll()
 * @method ApplicationReview[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ApplicationReviewRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ApplicationReview::class);
    }

    //    /**
    //     * @return ApplicationReview[] Returns an array of ApplicationReview objects
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

    //    public function findOneBySomeField($value): ?ApplicationReview
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
