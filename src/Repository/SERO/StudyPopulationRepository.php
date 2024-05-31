<?php

namespace App\Repository\SERO;

use App\Entity\SERO\StudyPopulation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<StudyPopulation>
 *
 * @method StudyPopulation|null find($id, $lockMode = null, $lockVersion = null)
 * @method StudyPopulation|null findOneBy(array $criteria, array $orderBy = null)
 * @method StudyPopulation[]    findAll()
 * @method StudyPopulation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StudyPopulationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StudyPopulation::class);
    }

    //    /**
    //     * @return StudyPopulation[] Returns an array of StudyPopulation objects
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

    //    public function findOneBySomeField($value): ?StudyPopulation
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
