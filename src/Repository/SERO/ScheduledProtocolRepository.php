<?php

namespace App\Repository\SERO;

use App\Entity\SERO\ScheduledProtocol;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ScheduledProtocol>
 *
 * @method ScheduledProtocol|null find($id, $lockMode = null, $lockVersion = null)
 * @method ScheduledProtocol|null findOneBy(array $criteria, array $orderBy = null)
 * @method ScheduledProtocol[]    findAll()
 * @method ScheduledProtocol[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ScheduledProtocolRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ScheduledProtocol::class);
    }

    //    /**
    //     * @return ScheduledProtocol[] Returns an array of ScheduledProtocol objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('s.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?ScheduledProtocol
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
