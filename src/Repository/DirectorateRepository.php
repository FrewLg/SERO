<?php

namespace App\Repository;

use App\Entity\Directorate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Directorate>
 *
 * @method Directorate|null find($id, $lockMode = null, $lockVersion = null)
 * @method Directorate|null findOneBy(array $criteria, array $orderBy = null)
 * @method Directorate[]    findAll()
 * @method Directorate[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DirectorateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Directorate::class);
    }

//    /**
//     * @return Directorate[] Returns an array of Directorate objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('d.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Directorate
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
