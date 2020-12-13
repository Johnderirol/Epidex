<?php

namespace App\Controller;

use App\Entity\Rayon;
use App\Repository\CategorieRepository;
use App\Repository\MissionCibleRepository;
use App\Repository\RatingRepository;
use App\Repository\RayonRepository;
use App\Repository\SecteurRepository;
use App\Repository\SkillRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController{
    
    /**
    * @Route("/", name="homepage")
    */
    public function home(){
                
        return $this->render(
            'home.html.twig',
        );
    }
    
    /**
    * @Route("admin/dashboard", name="dashboard")
    */
    public function dashboard(SkillRepository $skillRepo, MissionCibleRepository $missionRepo , CategorieRepository $catRepo, RayonRepository $rayonRepo, SecteurRepository $secteurRepo )
    {

        $collaborateur = $this->getUser();
        $skills = $skillRepo->findAvgNotesByCategorie();
        $cat = $catRepo->findAll();
        $rayon = $rayonRepo->findAll();
        $secteur = $secteurRepo->findAll();
        $mission = $missionRepo->findAll();

        return $this->render('admin/dashboard.html.twig',[
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
    */
    public function ManagerDashboard(SkillRepository $skillRepo, CategorieRepository $catRepo, RayonRepository $rayonRepo, SecteurRepository $secteurRepo )
    {
        $collaborateur = $this->getUser();
        $user = $this->getUser()->getId();
        $secteur = $secteurRepo->findByResponsable($user);
        $rayon = $rayonRepo->findBySecteur($secteur);
        $skills = $skillRepo->findAvgNotesBySecteur($secteur);
        $cat = $catRepo->findAll();


        return $this->render('manager/dashboard.html.twig',[
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