<?php

namespace App\Repository;

use App\Entity\RatingEtoile;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RatingEtoile|null find($id, $lockMode = null, $lockVersion = null)
 * @method RatingEtoile|null findOneBy(array $criteria, array $orderBy = null)
 * @method RatingEtoile[]    findAll()
 * @method RatingEtoile[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RatingEtoileRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RatingEtoile::class);
    }

    // /**
    //  * @return RatingEtoile[] Returns an array of RatingEtoile objects
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
    public function findOneBySomeField($value): ?RatingEtoile
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
