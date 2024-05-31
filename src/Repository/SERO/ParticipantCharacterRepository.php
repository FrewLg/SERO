<?php

namespace App\Repository\SERO;

use App\Entity\SERO\ParticipantCharacter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ParticipantCharacter>
 *
 * @method ParticipantCharacter|null find($id, $lockMode = null, $lockVersion = null)
 * @method ParticipantCharacter|null findOneBy(array $criteria, array $orderBy = null)
 * @method ParticipantCharacter[]    findAll()
 * @method ParticipantCharacter[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ParticipantCharacterRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ParticipantCharacter::class);
    }

    //    /**
    //     * @return ParticipantCharacter[] Returns an array of ParticipantCharacter objects
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

    //    public function findOneBySomeField($value): ?ParticipantCharacter
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
