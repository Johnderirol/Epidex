<?php

namespace App\Repository;

use App\Entity\Skill;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Skill|null find($id, $lockMode = null, $lockVersion = null)
 * @method Skill|null findOneBy(array $criteria, array $orderBy = null)
 * @method Skill[]    findAll()
 * @method Skill[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SkillRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Skill::class);
    }

    public function findAvgNotesByCategorie()
    {
        return $this->createQueryBuilder('s')
                    ->select('s as competence, AVG(r.note) as avgNotes, c as category')
                    ->join('s.ratings','r')
                    ->join('s.category','c')
                    ->groupBy('competence')
                    ->getQuery()
                    ->getArrayResult();
    }

    public function findAvgNotesByRayon($id)
    {
        return $this->createQueryBuilder('s')
                    ->select('s as competence, AVG(r.note) as avgNotes, c as category, y.title')
                    ->join('s.category','c')
                    ->join('s.ratings','r')
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

    public function findAvgNotesBySecteur($id)
    {
        return $this->createQueryBuilder('s')
                    ->select('s as competence, AVG(r.note) as avgNotes, c as category')
                    ->join('s.category','c')
                    ->join('s.ratings','r')
                    ->join('r.evaluation','e')
                    ->join('r.rayon','y')
                    ->join('y.secteur','t')
                    ->andWhere('y.secteur = :val')
                    ->andWhere('t.responsable = e.auteur')
                    ->setParameter('val', $id)
                    ->groupBy('competence')
                    ->getQuery()
                    ->getResult();
    }

    public function findDescription($id)
    {
        return $this->createQueryBuilder('s')
                    ->select('s.description as description')
                    ->andWhere('s.id = :val')
                    ->setParameter('val', $id)
                    ->getQuery()
                    ->getResult();
    }
    

    // /**
    //  * @return Skill[] Returns an array of Skill objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Skill
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
