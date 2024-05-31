<?php

namespace App\Repository\SERO;

use App\Entity\SERO\MultiSiteCollaboration;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MultiSiteCollaboration>
 *
 * @method MultiSiteCollaboration|null find($id, $lockMode = null, $lockVersion = null)
 * @method MultiSiteCollaboration|null findOneBy(array $criteria, array $orderBy = null)
 * @method MultiSiteCollaboration[]    findAll()
 * @method MultiSiteCollaboration[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MultiSiteCollaborationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MultiSiteCollaboration::class);
    }

    //    /**
    //     * @return MultiSiteCollaboration[] Returns an array of MultiSiteCollaboration objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('m.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?MultiSiteCollaboration
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
