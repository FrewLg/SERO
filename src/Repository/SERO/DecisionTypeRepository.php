<?php

namespace App\Repository\SERO;

use App\Entity\SERO\DecisionType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DecisionType>
 *
 * @method DecisionType|null find($id, $lockMode = null, $lockVersion = null)
 * @method DecisionType|null findOneBy(array $criteria, array $orderBy = null)
 * @method DecisionType[]    findAll()
 * @method DecisionType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DecisionTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DecisionType::class);
    }

    //    /**
    //     * @return DecisionType[] Returns an array of DecisionType objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('d')
    //            ->andWhere('d.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('d.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?DecisionType
    //    {
    //        return $this->createQueryBuilder('d')
    //            ->andWhere('d.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
