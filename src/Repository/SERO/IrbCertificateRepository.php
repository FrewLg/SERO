<?php

namespace App\Repository\SERO;

use App\Entity\SERO\IrbCertificate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<IrbCertificate>
 *
 * @method IrbCertificate|null find($id, $lockMode = null, $lockVersion = null)
 * @method IrbCertificate|null findOneBy(array $criteria, array $orderBy = null)
 * @method IrbCertificate[]    findAll()
 * @method IrbCertificate[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IrbCertificateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, IrbCertificate::class);
    }

    //    /**
    //     * @return IrbCertificate[] Returns an array of IrbCertificate objects
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

    //    public function findOneBySomeField($value): ?IrbCertificate
    //    {
    //        return $this->createQueryBuilder('i')
    //            ->andWhere('i.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
