<?php

namespace App\Repository\SERO;

use App\Entity\SERO\ApplicationFeedback;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ApplicationFeedback>
 *
 * @method ApplicationFeedback|null find($id, $lockMode = null, $lockVersion = null)
 * @method ApplicationFeedback|null findOneBy(array $criteria, array $orderBy = null)
 * @method ApplicationFeedback[]    findAll()
 * @method ApplicationFeedback[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ApplicationFeedbackRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ApplicationFeedback::class);
    }

    //    /**
    //     * @return ApplicationFeedback[] Returns an array of ApplicationFeedback objects
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

    //    public function findOneBySomeField($value): ?ApplicationFeedback
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
