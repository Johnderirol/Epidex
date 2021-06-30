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

    public function findAvgNotesMagasin()
    {
        return $this->createQueryBuilder('s')
                    ->select('s as competence, AVG(r.note) as avgNotes, c as category')
                    ->join('s.ratings','r')
                    ->join('s.category','c')
                    ->join('r.evaluation','e')
                    ->join('r.rayon','y')
                    ->join('y.secteur','t')
                    ->andWhere('t.responsable = e.auteur')
                    ->groupBy('competence')
                    ->getQuery()
                    ->getArrayResult();
    }

    public function findNotesByRayon($id)
    {
        return $this->createQueryBuilder('s')
                    ->select('s as competence, r as note, c as category, y.title')
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

    public function findNotesByCollab($id)
    {
        return $this->createQueryBuilder('s')
                    ->select('s as competence, s.title as skillTitle, s.id as skillId, k.nom as proprio, r.note as note, c.slug as category, k.id as proprioID')
                    ->join('s.category','c')
                    ->join('s.ratings','r')
                    ->join('r.evaluation','e')
                    ->join('r.rayon','y')
                    ->join('r.collaborateur','k')
                    ->join('y.secteur','t')
                    ->andWhere('k.id = :val')
                    ->andWhere('t.responsable = e.auteur')
                    ->setParameter('val', $id)
                    ->getQuery()
                    ->getResult();
    }

    public function findNotesByMissionCollab($id)
    {
        return $this->createQueryBuilder('s')
                    ->select('s as competence, s.title as skillTitle, s.id as skillId, k.nom as proprio, r.note as note, c.slug as category, k.id as proprioID')
                    ->join('s.category','c')
                    ->join('s.ratings','r')
                    ->join('r.evaluation','e')
                    ->join('r.collaborateur','k')
                    ->andWhere('k.id = :val')
                    ->setParameter('val', $id)
                    ->getQuery()
                    ->getResult();
    }

    public function findAvgNotesByRayon($id)
    {
        return $this->createQueryBuilder('s')
                    ->select('s as competence, s.title as skillTitle, s.id as skillId, y.title as proprio, AVG(r.note) as note, c.slug as category, y.id as proprioID')
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
                    ->select('s as competence, s.title as skillTitle, s.id as skillId, t.title as proprio, AVG(r.note) as note, c.slug as category, t.id as proprioID')
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

    public function findAvgNotesByMission($id)
    {
        return $this->createQueryBuilder('s')
                    ->select('s as competence, s.title as skillTitle, s.id as skillId, AVG(r.note) as note, c.slug as category')
                    ->join('s.category','c')
                    ->join('s.ratings','r')
                    ->join('r.evaluation','e')
                    ->join('e.collaborateur', 'k')
                    ->join('k.rayon','y')
                    ->join('k.mission','m')
                    ->join('y.secteur','t')
                    ->andWhere('m.id = :val')
                    ->andWhere('t.responsable = e.auteur')
                    ->setParameter('val', $id)
                    ->groupBy('competence')
                    ->getQuery()
                    ->getResult();
    }

    public function findSkillIdBySecteur($id)
    {
        return $this->createQueryBuilder('s')
                    ->select('s as competence, s.id as skillId, t.title as secteur')
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

    public function findSkillIdByRayon($id)
    {
        return $this->createQueryBuilder('s')
                    ->select('s as competence, s.id as skillId, t.title as secteur')
                    ->join('s.ratings','r')
                    ->join('r.evaluation','e')
                    ->join('r.rayon','y')
                    ->join('y.secteur','t')
                    ->andWhere('r.rayon = :val')
                    ->andWhere('t.responsable = e.auteur')
                    ->setParameter('val', $id)
                    ->groupBy('competence')
                    ->getQuery()
                    ->getResult();
    }

    public function findSkillIdByMission($id)
    {
        return $this->createQueryBuilder('s')
                    ->select('s as competence, s.id as skillId')
                    ->join('s.ratings','r')
                    ->join('r.evaluation','e')
                    ->join('e.collaborateur','k')
                    ->join('k.mission','m')
                    ->andWhere('m.id = :val')
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
    
    public function findKillsBySecteur($id)
    {
        return $this->createQueryBuilder('s')
                    ->select('s.id as Skills')
                    ->join('s.category','c')
                    ->join('s.ratings','r')
                    ->join('r.evaluation','e')
                    ->join('r.rayon','y')
                    ->join('y.secteur','t')
                    ->andWhere('y.secteur = :val')
                    ->andWhere('t.responsable = e.auteur')
                    ->setParameter('val', $id)
                    ->groupBy('Skills')
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
