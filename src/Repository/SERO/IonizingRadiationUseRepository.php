<?php

namespace App\Repository\SERO;

use App\Entity\SERO\IonizingRadiationUse;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<IonizingRadiationUse>
 *
 * @method IonizingRadiationUse|null find($id, $lockMode = null, $lockVersion = null)
 * @method IonizingRadiationUse|null findOneBy(array $criteria, array $orderBy = null)
 * @method IonizingRadiationUse[]    findAll()
 * @method IonizingRadiationUse[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IonizingRadiationUseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, IonizingRadiationUse::class);
    }

    //    /**
    //     * @return IonizingRadiationUse[] Returns an array of IonizingRadiationUse objects
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

    //    public function findOneBySomeField($value): ?IonizingRadiationUse
    //    {
    //        return $this->createQueryBuilder('i')
    //            ->andWhere('i.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
