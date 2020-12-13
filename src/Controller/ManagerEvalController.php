<?php

namespace App\Controller;

use App\Entity\Evaluation;
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

class ManagerEvalController extends AbstractController
{
    /**
     * @Route("/manager/eval", name="manager_eval")
     */
    public function index(EvaluationRepository $repoEval, CollaborateurRepository $repo, SecteurRepository $repoSecteur, RayonRepository $repoRayon)
    {
        $user = $this->getUser()->getId();
        $secteur = $repoSecteur->findByResponsable($user);
        $rayon = $repoRayon->findBySecteur($secteur);

        $collaborateur = [];
        foreach ($rayon as $rayonid) {
            $collaborateur [] = $repo->findByRayon($rayonid);
        }
        
        $evaluations = [];
        foreach ($collaborateur as $collaborateurs){
            $evaluations [] = $repoEval->findByCollaborateur($collaborateurs);
        }
        

        return $this->render('manager/evaluations/index.html.twig', [
            'evaluations' => $evaluations,
        ]);
    }


    /**
     * Permet de suppimer une évaluation
     * @Route("/manager/delete_evaluation/{id}", name="manager_evaluation_delete")
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

        return $this->redirectToRoute('manager_eval');
    }

    /**
     * Permet de retrouver les évaluations d'un secteur
     * @Route("/manager/evaluations/secteur/{id}", name="manager_eval_secteur")
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

        return $this->render('manager/evaluations/secteur.html.twig', [
            'collaborateurs' => $collaborateurs, 
            'categories'=>$cat,
            'skillSecteur'=> $skillSecteur,
            'skillRayon'=>$skillRayon,
            'rayons'=>$rayons,
            'secteurs'=>$secteur,
        ]);
    }

    /**
     * Permet de retrouver les évaluations d'un rayon
     * @Route("/manager/evaluations/rayon/{id}", name="manager_eval_rayon")
     */
    public function showRayon( $id, CollaborateurRepository $repoCollab, SkillRepository $skillRepo, CategorieRepository $catRepo, RayonRepository $rayonRepo, RatingRepository $ratRepo) 
    {
        $collaborateurs = $repoCollab->findByRayon($id);
        $skills = $skillRepo->findAvgNotesByRayon($id);
        $cat = $catRepo->findAll();
        $rayon = $rayonRepo->findById($id);
        $note = $ratRepo->findAll();


        return $this->render('manager/evaluations/rayon.html.twig', [
            'collaborateurs' => $collaborateurs, 
            'rayons'=>$rayon,
            'categories'=>$cat,
            'skills'=> $skills,
            'ratings'=>$note,
        ]);
    }

}
