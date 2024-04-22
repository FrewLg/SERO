<?php

namespace App\Repository\SERO;

use App\Entity\SERO\MeetingSchedule;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MeetingSchedule>
 *
 * @method MeetingSchedule|null find($id, $lockMode = null, $lockVersion = null)
 * @method MeetingSchedule|null findOneBy(array $criteria, array $orderBy = null)
 * @method MeetingSchedule[]    findAll()
 * @method MeetingSchedule[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MeetingScheduleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MeetingSchedule::class);
    }

    //    /**
    //     * @return MeetingSchedule[] Returns an array of MeetingSchedule objects
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

    //    public function findOneBySomeField($value): ?MeetingSchedule
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
