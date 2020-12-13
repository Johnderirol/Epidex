<?php

namespace App\Repository;

use App\Entity\PDI;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PDI|null find($id, $lockMode = null, $lockVersion = null)
 * @method PDI|null findOneBy(array $criteria, array $orderBy = null)
 * @method PDI[]    findAll()
 * @method PDI[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PDIRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PDI::class);
    }

    // /**
    //  * @return PDI[] Returns an array of PDI objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PDI
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
