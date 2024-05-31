<?php

namespace App\Repository\SERO;

use App\Entity\SERO\SpecialResRequirement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SpecialResRequirement>
 *
 * @method SpecialResRequirement|null find($id, $lockMode = null, $lockVersion = null)
 * @method SpecialResRequirement|null findOneBy(array $criteria, array $orderBy = null)
 * @method SpecialResRequirement[]    findAll()
 * @method SpecialResRequirement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SpecialResRequirementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SpecialResRequirement::class);
    }

    //    /**
    //     * @return SpecialResRequirement[] Returns an array of SpecialResRequirement objects
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

    //    public function findOneBySomeField($value): ?SpecialResRequirement
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
