<?php

namespace App\Repository;

use App\Entity\Etoile;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Etoile|null find($id, $lockMode = null, $lockVersion = null)
 * @method Etoile|null findOneBy(array $criteria, array $orderBy = null)
 * @method Etoile[]    findAll()
 * @method Etoile[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EtoileRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Etoile::class);
    }

    // /**
    //  * @return Etoile[] Returns an array of Etoile objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Etoile
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
