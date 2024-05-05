<?php

namespace App\Repository\SERO;

use App\Entity\SERO\StudyType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<StudyType>
 *
 * @method StudyType|null find($id, $lockMode = null, $lockVersion = null)
 * @method StudyType|null findOneBy(array $criteria, array $orderBy = null)
 * @method StudyType[]    findAll()
 * @method StudyType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StudyTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StudyType::class);
    }

    //    /**
    //     * @return StudyType[] Returns an array of StudyType objects
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

    //    public function findOneBySomeField($value): ?StudyType
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
