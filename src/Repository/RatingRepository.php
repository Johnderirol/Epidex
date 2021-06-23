<?php

namespace App\Repository;

use App\Entity\Rating;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Rating|null find($id, $lockMode = null, $lockVersion = null)
 * @method Rating|null findOneBy(array $criteria, array $orderBy = null)
 * @method Rating[]    findAll()
 * @method Rating[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RatingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Rating::class);
    }

    public function findNotesByRayon($id)
    {
        return $this->createQueryBuilder('r')
                    ->select('AVG(r.note) as note, s as competence, s.id as SkillId,')
                    ->join('r.competences','s')
                    ->join('r.evaluation','e')
                    ->join('r.rayon','y')
                    ->join('y.secteur','t')
                    ->andWhere('y.id = :val')
                    ->andWhere('t.responsable = e.auteur')
                    ->setParameter('val', $id)
                    ->groupBy('competence')
                    ->getQuery()
                    ->getResult();
    }

    public function findNotesBySecteur($id)
    {
        return $this->createQueryBuilder('r')
                    ->select(' r.note as note, s.id as competence, y.id as rayon')
                    ->join('r.competences','s')
                    ->join('r.evaluation','e')
                    ->join('r.rayon','y')
                    ->join('y.secteur','t')
                    ->andWhere('t.id = :val')
                    ->andWhere('t.responsable = e.auteur')
                    ->setParameter('val', $id)
                    ->getQuery()
                    ->getResult();
    }
    
    public function findNotesBySkills($id)
    {
        return $this->createQueryBuilder('r')
                    ->select('r.note as note, s.id as skills')
                    ->join('r.competences','s')
                    ->andWhere('s.id = :val')
                    ->setParameter('val', $id)
                    ->groupBy('skills')
                    ->getQuery()
                    ->getResult();
    }

    // /**
    //  * @return Rating[] Returns an array of Rating objects
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
    public function findOneBySomeField($value): ?Rating
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
