<?php

namespace App\Repository;

use App\Entity\RatingLeader;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RatingLeader|null find($id, $lockMode = null, $lockVersion = null)
 * @method RatingLeader|null findOneBy(array $criteria, array $orderBy = null)
 * @method RatingLeader[]    findAll()
 * @method RatingLeader[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RatingLeaderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RatingLeader::class);
    }

    // /**
    //  * @return RatingLeader[] Returns an array of RatingLeader objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?RatingLeader
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
