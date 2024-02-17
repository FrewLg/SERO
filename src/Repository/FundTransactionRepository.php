<?php

namespace App\Repository;

use App\Entity\FundTransaction;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FundTransaction>
 *
 * @method FundTransaction|null find($id, $lockMode = null, $lockVersion = null)
 * @method FundTransaction|null findOneBy(array $criteria, array $orderBy = null)
 * @method FundTransaction[]    findAll()
 * @method FundTransaction[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FundTransactionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FundTransaction::class);
    }

//    /**
//     * @return FundTransaction[] Returns an array of FundTransaction objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('f.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?FundTransaction
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
