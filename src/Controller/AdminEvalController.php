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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminEvalController extends AbstractController
{
    /**
     * @Route("/admin/evaluations", name="admin_eval")
     * @IsGranted("ROLE_ADMIN")
     */
    public function indexEvaluation(EvaluationRepository $repo)
    {
        $evaluations = $repo->findAll();

        return $this->render('evaluation/index.html.twig', [
            'evaluations' => $evaluations,
        ]);
    }

    /**
     * @Route("/manager/evaluations", name="manager_eval")
     * @IsGranted("ROLE_MANAGER")
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
        

        return $this->render('evaluation/index.html.twig', [
            'evaluations' => $evaluations,
        ]);
    }

    /**
     * Permet de retrouver les évaluations d'un rayon
     * @Route("/admin/evaluations/rayon/{id}", name="admin_eval_rayon")
     * @Route("/manager/evaluations/rayon/{id}", name="manager_eval_rayon")
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_MANAGER')")
     */
    public function showRayon( $id, CollaborateurRepository $repoCollab, SkillRepository $skillRepo, CategorieRepository $catRepo, RayonRepository $rayonRepo, RatingRepository $ratRepo) 
    {
        $collaborateurs = $repoCollab->findByRayon($id);
        $skills = $skillRepo->findAvgNotesByRayon($id);
        $cat = $catRepo->findAll();
        $rayon = $rayonRepo->findById($id);
        $note = $ratRepo->findAll();


        return $this->render('evaluation/rayon.html.twig', [
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
     * @Route("/manager/evaluations/secteur/{id}", name="manager_eval_secteur")
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_MANAGER')")
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

        return $this->render('evaluation/secteur.html.twig', [
            'collaborateurs' => $collaborateurs, 
            'categories'=>$cat,
            'skillSecteur'=> $skillSecteur,
            'skillRayon'=>$skillRayon,
            'rayons'=>$rayons,
            'secteurs'=>$secteur,
        ]);
    
    
    }

    
}
