<?php

namespace App\Controller;

use App\Entity\Rayon;
use App\Repository\RayonRepository;
use App\Repository\SkillRepository;
use App\Repository\RatingRepository;
use App\Repository\SecteurRepository;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\MissionCibleRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController{
    
    /**
    * @Route("/", name="homepage")
    * @IsGranted("ROLE_USER")
    */
    public function home(){
                
        return $this->render(
            'home.html.twig',
        );
    }
    
    /**
    * @Route("admin/dashboard", name="dashboard")
    * @IsGranted("ROLE_ADMIN")
    */
    public function dashboard(SkillRepository $skillRepo, MissionCibleRepository $missionRepo , CategorieRepository $catRepo, RayonRepository $rayonRepo, SecteurRepository $secteurRepo )
    {

        $collaborateur = $this->getUser();
        $skills = $skillRepo->findAvgNotesByCategorie();
        $cat = $catRepo->findAll();
        $rayon = $rayonRepo->findAll();
        $secteur = $secteurRepo->findAll();
        $mission = $missionRepo->findAll();

        return $this->render('dashboard.html.twig',[
            'collaborateur'=>$collaborateur,
            'categories'=>$cat,
            'skills'=> $skills,
            'rayons'=>$rayon,
            'secteurs'=>$secteur,
            'missions'=>$mission,
        ]
        );

    }

    /**
    * @Route("manager/dashboard", name="manager_dashboard")
    * @IsGranted("ROLE_MANAGER")
    */
    public function ManagerDashboard(SkillRepository $skillRepo, CategorieRepository $catRepo, RayonRepository $rayonRepo, SecteurRepository $secteurRepo )
    {
        $collaborateur = $this->getUser();
        $user = $this->getUser()->getId();
        $secteur = $secteurRepo->findByResponsable($user);
        $rayon = $rayonRepo->findBySecteur($secteur);
        $skills = $skillRepo->findAvgNotesBySecteur($secteur);
        $cat = $catRepo->findAll();


        return $this->render('dashboard.html.twig',[
            'collaborateur'=>$collaborateur,
            'categories'=>$cat,
            'skills'=> $skills,
            'rayons'=>$rayon,
            'secteurs'=>$secteur,
        ]
        );
    
    }

    
}


?>