<?php

namespace App\Repository;

use App\Entity\Collaborateur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Collaborateur|null find($id, $lockMode = null, $lockVersion = null)
 * @method Collaborateur|null findOneBy(array $criteria, array $orderBy = null)
 * @method Collaborateur[]    findAll()
 * @method Collaborateur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CollaborateurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Collaborateur::class);
    }

    public function findByStatut(){
        return $this->createQueryBuilder('c')
                    ->andWhere('c.statut = :val')
                    ->setParameter('val', "Cadre")
                    ->orderBy('c.nom', 'ASC')
                    ->getQuery()
                    ->getResult();
    }

    public function findBySecteur($id)
    {
        return $this->createQueryBuilder('c')
                    ->select('c as collaborateur')
                    ->join('c.rayon','r')
                    ->andWhere('r.secteur = :val')
                    ->setParameter('val', $id)
                    ->groupBy('collaborateur')
                    ->getQuery()
                    ->getResult();
    }

    public function findStatutByRayon($id)
    {
        return $this->createQueryBuilder('c')
                    ->select('c.statut as statut')
                    ->join('c.rayon','r')
                    ->andWhere('c.rayon = :val')
                    ->setParameter('val', $id)
                    ->groupBy('statut')
                    ->getQuery()
                    ->getResult();
    }

    public function findByMission($id)
    {
        return $this->createQueryBuilder('c')
                    ->select('c as collaborateur, c.id as id')
                    ->join('c.mission','m')
                    ->andWhere('m.id = :val')
                    ->setParameter('val', $id)
                    ->groupBy('collaborateur')
                    ->getQuery()
                    ->getResult();
    }

    // /**
    //  * @return Collaborateur[] Returns an array of Collaborateur objects
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
    public function findOneBySomeField($value): ?Collaborateur
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
