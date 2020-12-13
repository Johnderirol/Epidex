<?php

namespace App\Repository;

use App\Entity\CompEtoile;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CompEtoile|null find($id, $lockMode = null, $lockVersion = null)
 * @method CompEtoile|null findOneBy(array $criteria, array $orderBy = null)
 * @method CompEtoile[]    findAll()
 * @method CompEtoile[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CompEtoileRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CompEtoile::class);
    }

    // /**
    //  * @return CompEtoile[] Returns an array of CompEtoile objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CompEtoile
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
