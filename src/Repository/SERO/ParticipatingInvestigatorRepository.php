<?php

namespace App\Repository\SERO;

use App\Entity\SERO\ParticipatingInvestigator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ParticipatingInvestigator>
 *
 * @method ParticipatingInvestigator|null find($id, $lockMode = null, $lockVersion = null)
 * @method ParticipatingInvestigator|null findOneBy(array $criteria, array $orderBy = null)
 * @method ParticipatingInvestigator[]    findAll()
 * @method ParticipatingInvestigator[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ParticipatingInvestigatorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ParticipatingInvestigator::class);
    }

    //    /**
    //     * @return ParticipatingInvestigator[] Returns an array of ParticipatingInvestigator objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?ParticipatingInvestigator
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
