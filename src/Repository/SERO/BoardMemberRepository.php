<?php

namespace App\Repository\SERO;

use App\Repository\UserRepository;
use App\Entity\SERO\BoardMember;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<BoardMember>
 *
 * @method BoardMember|null find($id, $lockMode = null, $lockVersion = null)
 * @method BoardMember|null findOneBy(array $criteria, array $orderBy = null)
 * @method BoardMember[]    findAll()
 * @method BoardMember[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BoardMemberRepository extends ServiceEntityRepository
{
    private $userRepository;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BoardMember::class);
        // $this->userRepository=$userRepository;

    }
    public function getData($filters=[])
    {
        $qb=$this->createQueryBuilder('b');
         
        if(isset($filters['search']) && $filters['search']){
           $res= $this->userRepository->getData(["name"=>$filters['search']]);
         
           $qb->andWhere("b.user in (:users)")
           ->setParameter("users",$res->getResult());
        }
        
            return $qb->getQuery()
         
        ;
    }
    //    /**
    //     * @return BoardMember[] Returns an array of BoardMember objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('b.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?BoardMember
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
