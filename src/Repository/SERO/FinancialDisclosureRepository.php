<?php

namespace App\Repository\SERO;

use App\Entity\SERO\FinancialDisclosure;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FinancialDisclosure>
 *
 * @method FinancialDisclosure|null find($id, $lockMode = null, $lockVersion = null)
 * @method FinancialDisclosure|null findOneBy(array $criteria, array $orderBy = null)
 * @method FinancialDisclosure[]    findAll()
 * @method FinancialDisclosure[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FinancialDisclosureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FinancialDisclosure::class);
    }

    //    /**
    //     * @return FinancialDisclosure[] Returns an array of FinancialDisclosure objects
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

    //    public function findOneBySomeField($value): ?FinancialDisclosure
    //    {
    //        return $this->createQueryBuilder('f')
    //            ->andWhere('f.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
