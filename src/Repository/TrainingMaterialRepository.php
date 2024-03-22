<?php

namespace App\Repository;

use App\Entity\TrainingMaterial;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TrainingMaterial>
 *
 * @method TrainingMaterial|null find($id, $lockMode = null, $lockVersion = null)
 * @method TrainingMaterial|null findOneBy(array $criteria, array $orderBy = null)
 * @method TrainingMaterial[]    findAll()
 * @method TrainingMaterial[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TrainingMaterialRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TrainingMaterial::class);
    }

//    /**
//     * @return TrainingMaterial[] Returns an array of TrainingMaterial objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?TrainingMaterial
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
