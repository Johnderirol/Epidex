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
        $skillsRayon = $skillRepo->findAvgNotesByRayon($id);
        $skills = $skillRepo->findSkillIdByRayon($id);
        $cat = $catRepo->findAll();
        $rayon = $rayonRepo->findById($id);
        $note = $ratRepo->findAll();

        //on récupère les données de chaque collab dans un seul tableau
        $skillColab = [];
        foreach ($collaborateurs as $collabid) {
            $skillColab[] = $skillRepo->findNotesByCollab($collabid);
        }   

        //on nomme les clés de skills avec SkillId
        $skills = array_column($skills, null, 'skillId');

        //après avoir compté le nombre de collab, nous fusionnons les collab avec les compétences
        $countCol = count($skillColab);
        $out = [];
        for ($i = 0; $i <= $countCol; $i++)  {
            if(empty($skillColab[$i])){
            } else {
            $out[$i] = array_column($skillColab[$i], null, 'skillId');
            $out[$i] = array_replace($skills, $out[$i]);
            }
        }
        
        //on renome les clés du tableau final avec les identifiants des collaborateurs
        $num = [];
        foreach($skillColab as $sk) {
            foreach ($sk as $key => $value) {
                $num[] = $value['proprioID'];
            }
        }
        $num = array_unique($num);
        $countNum = count($num);
        for ($i = 0; $i <= $countNum; $i++) {
            $out = array_combine($num, $out);
        }

        return $this->render('evaluation/rayon.html.twig', [
            'collaborateurs' => $collaborateurs, 
            'rayons'=>$rayon,
            'categories'=>$cat,
            'skills'=> $skillsRayon,
            'ratings'=>$out,
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
        $skills = $skillRepo->findSkillIdBySecteur($id);
        $cat = $catRepo->findAll();
        $secteur = $secteurRepo->findById($id);
        
        
        //on récupère les données de chaque rayons dans un seul tableau
        $skillRayon = [];
        foreach ($rayons as $rayonid) {
            $skillRayon[] = $skillRepo->findAvgNotesByRayon($rayonid);
        }   

        //on nomme les clés de skills avec SkillId
        $skills = array_column($skills, null, 'skillId');

        //après avoir compté le nombre de rayons, nous fusionnons les rayons avec les compétences
        $countRay = count($skillRayon);
        $out = [];
        for ($i = 0; $i <= $countRay; $i++)  {
            if(empty($skillRayon[$i])){
            } else {
            $out[$i] = array_column($skillRayon[$i], null, 'skillId');
            $out[$i] = array_replace($skills, $out[$i]);
            }
        }

        //on renome les clés du tableau final avec les identifiants des rayons
        $num = [];
        foreach($skillRayon as $sk) {
            foreach ($sk as $key => $value) {
                $num[] = $value['proprioID'];
            }
        }
        $num = array_unique($num);
        $countNum = count($num);
        for ($i = 0; $i <= $countNum; $i++) {
            $out = array_combine($num, $out);
        }


        return $this->render('evaluation/secteur.html.twig', [
            'collaborateurs' => $collaborateurs, 
            'categories'=>$cat,
            'skillSecteur'=> $skillSecteur,
            'rayons'=>$rayons,
            'secteurs'=>$secteur,
            'compSecteur'=>$out,
        ]);
    
    
    }

    
}
