<?php

namespace App\Repository;

use App\Entity\MissionCible;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MissionCible|null find($id, $lockMode = null, $lockVersion = null)
 * @method MissionCible|null findOneBy(array $criteria, array $orderBy = null)
 * @method MissionCible[]    findAll()
 * @method MissionCible[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MissionCibleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MissionCible::class);
    }

    // /**
    //  * @return MissionCible[] Returns an array of MissionCible objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MissionCible
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
