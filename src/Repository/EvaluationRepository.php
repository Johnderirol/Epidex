<?php

namespace App\Repository;

use App\Entity\Evaluation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Evaluation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Evaluation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Evaluation[]    findAll()
 * @method Evaluation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EvaluationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Evaluation::class);
    }



    // /**
    //  * @return Evaluation[] Returns an array of Evaluation objects
    //  */

    public function findByCollabRayon($value)
    {
        return $this->createQueryBuilder('e')
            ->select('e as evaluation')
            ->join('e.collaborateur','c')
            ->join('c.rayon','y')
            ->join('y.secteur','t')
            ->andWhere('y.id = :val')
            ->andWhere('t.responsable = e.auteur')
            ->setParameter('val', $value)
            ->getQuery()
            ->getResult();
        
    }


    /*
    public function findOneBySomeField($value): ?Evaluation
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
