<?php

namespace App\Repository\SERO;

use App\Entity\SERO\ReviewAssignment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ReviewAssignment>
 *
 * @method ReviewAssignment|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReviewAssignment|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReviewAssignment[]    findAll()
 * @method ReviewAssignment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReviewAssignmentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReviewAssignment::class);
    }

    //    /**
    //     * @return ReviewAssignment[] Returns an array of ReviewAssignment objects
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

    //    public function findOneBySomeField($value): ?ReviewAssignment
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function getActiveApplication()
    {
        return $this->createQueryBuilder('i')
            ->join("App:SERO\Application", "r", "with", "r.id=i.application")
            ->where('r.status in (:status)')->setParameter('status',[1,2,3,4])
            ->getQuery()
        ;
    }

}
