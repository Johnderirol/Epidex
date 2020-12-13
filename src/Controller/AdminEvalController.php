<?php

namespace App\Controller;

use App\Entity\Rating;
use App\Entity\Evaluation;
use App\Entity\Collaborateur;
use App\Repository\RayonRepository;
use App\Repository\SkillRepository;
use App\Repository\RatingRepository;
use App\Repository\SecteurRepository;
use App\Repository\CategorieRepository;
use App\Repository\EvaluationRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CollaborateurRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminEvalController extends AbstractController
{
    /**
     * @Route("/admin/evaluations", name="admin_eval")
     */
    public function indexEvaluation(EvaluationRepository $repo)
    {
        $evaluations = $repo->findAll();

        return $this->render('admin/evaluations/evaluation.html.twig', [
            'evaluations' => $evaluations,
        ]);
    }


    /**
     * Permet de retrouver les évaluations d'un rayon
     * @Route("/admin/evaluations/rayon/{id}", name="admin_eval_rayon")
     */
    public function showRayon( $id, CollaborateurRepository $repoCollab, SkillRepository $skillRepo, CategorieRepository $catRepo, RayonRepository $rayonRepo, RatingRepository $ratRepo) 
    {
        $collaborateurs = $repoCollab->findByRayon($id);
        $skills = $skillRepo->findAvgNotesByRayon($id);
        $cat = $catRepo->findAll();
        $rayon = $rayonRepo->findById($id);
        $note = $ratRepo->findAll();


        return $this->render('admin/evaluations/rayon.html.twig', [
            'collaborateurs' => $collaborateurs, 
            'rayons'=>$rayon,
            'categories'=>$cat,
            'skills'=> $skills,
            'ratings'=>$note,
        ]);
    }
    
    /**
     * Permet de retrouver les évaluations d'un secteur
     * @Route("/admin/evaluations/secteur/{id}", name="admin_eval_secteur")
     */
    public function showSecteur( $id, SkillRepository $skillRepo, EntityManagerInterface $manager, CollaborateurRepository $repoCollab, RayonRepository $rayonRepo, CategorieRepository $catRepo, RatingRepository $ratRepo, SecteurRepository $secteurRepo) 
    {
        $rayons = $rayonRepo->findBySecteur($id);
        $collaborateurs = $repoCollab->findBySecteur($id);
        $skillSecteur = $skillRepo->findAvgNotesBySecteur($id);
        $cat = $catRepo->findAll();
        $secteur = $secteurRepo->findById($id);
        
        $skillRayon = [];
        foreach ($rayons as $rayonid) {
            $skillRayon[] = $skillRepo->findAvgNotesByRayon($rayonid);
        }
        dump($skillRayon);

        return $this->render('admin/evaluations/secteur.html.twig', [
            'collaborateurs' => $collaborateurs, 
            'categories'=>$cat,
            'skillSecteur'=> $skillSecteur,
            'skillRayon'=>$skillRayon,
            'rayons'=>$rayons,
            'secteurs'=>$secteur,
        ]);
    
    
    }


    /**
     * Permet de suppimer une évaluation
     * @Route("/admin/delete_evaluation/{id}", name="evaluation_delete")
     * @param Evaluation $evaluation
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function delete(Evaluation $evaluation, EntityManagerInterface $manager) {
        $manager->remove($evaluation);
        $manager->flush();

        $this->addFlash(
            'success',
            "L'évaluation a bien été supprimé de la base de donnée !"
        );

        return $this->redirectToRoute('admin_eval');
    }

    
}
