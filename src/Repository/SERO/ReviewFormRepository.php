<?php

namespace App\Repository\SERO;

use App\Entity\SERO\ReviewForm;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ReviewForm>
 *
 * @method ReviewForm|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReviewForm|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReviewForm[]    findAll()
 * @method ReviewForm[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReviewFormRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReviewForm::class);
    }

    //    /**
    //     * @return ReviewForm[] Returns an array of ReviewForm objects
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

    //    public function findOneBySomeField($value): ?ReviewForm
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
