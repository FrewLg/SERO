<?php

namespace App\Repository;

use App\Entity\TrainingRequestStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TrainingRequestStatus>
 *
 * @method TrainingRequestStatus|null find($id, $lockMode = null, $lockVersion = null)
 * @method TrainingRequestStatus|null findOneBy(array $criteria, array $orderBy = null)
 * @method TrainingRequestStatus[]    findAll()
 * @method TrainingRequestStatus[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TrainingRequestStatusRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TrainingRequestStatus::class);
    }

//    /**
//     * @return TrainingRequestStatus[] Returns an array of TrainingRequestStatus objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?TrainingRequestStatus
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
