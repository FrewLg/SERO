<?php

namespace App\Repository\SERO;

use App\Entity\SERO\InvestigationalNewDrug;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<InvestigationalNewDrug>
 *
 * @method InvestigationalNewDrug|null find($id, $lockMode = null, $lockVersion = null)
 * @method InvestigationalNewDrug|null findOneBy(array $criteria, array $orderBy = null)
 * @method InvestigationalNewDrug[]    findAll()
 * @method InvestigationalNewDrug[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InvestigationalNewDrugRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InvestigationalNewDrug::class);
    }

    //    /**
    //     * @return InvestigationalNewDrug[] Returns an array of InvestigationalNewDrug objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('i')
    //            ->andWhere('i.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('i.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?InvestigationalNewDrug
    //    {
    //        return $this->createQueryBuilder('i')
    //            ->andWhere('i.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
