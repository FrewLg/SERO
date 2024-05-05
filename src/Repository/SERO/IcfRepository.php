<?php

namespace App\Repository\SERO;

use App\Entity\SERO\Icf;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Icf>
 *
 * @method Icf|null find($id, $lockMode = null, $lockVersion = null)
 * @method Icf|null findOneBy(array $criteria, array $orderBy = null)
 * @method Icf[]    findAll()
 * @method Icf[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IcfRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Icf::class);
    }

    //    /**
    //     * @return Icf[] Returns an array of Icf objects
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

    //    public function findOneBySomeField($value): ?Icf
    //    {
    //        return $this->createQueryBuilder('i')
    //            ->andWhere('i.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
