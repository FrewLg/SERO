<?php

namespace App\Repository;

use App\Entity\TrainingRequest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TrainingRequest>
 *
 * @method TrainingRequest|null find($id, $lockMode = null, $lockVersion = null)
 * @method TrainingRequest|null findOneBy(array $criteria, array $orderBy = null)
 * @method TrainingRequest[]    findAll()
 * @method TrainingRequest[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TrainingRequestRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TrainingRequest::class);
    }

//    /**
//     * @return TrainingRequest[] Returns an array of TrainingRequest objects
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

//    public function findOneBySomeField($value): ?TrainingRequest
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
