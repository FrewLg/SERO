<?php

namespace App\Repository;

use App\Entity\TrainingTopic;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TrainingTopic>
 *
 * @method TrainingTopic|null find($id, $lockMode = null, $lockVersion = null)
 * @method TrainingTopic|null findOneBy(array $criteria, array $orderBy = null)
 * @method TrainingTopic[]    findAll()
 * @method TrainingTopic[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TrainingTopicRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TrainingTopic::class);
    }

//    /**
//     * @return TrainingTopic[] Returns an array of TrainingTopic objects
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

//    public function findOneBySomeField($value): ?TrainingTopic
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
