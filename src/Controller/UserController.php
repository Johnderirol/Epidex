<?php

namespace App\Controller;

use App\Entity\Skill;
use App\Entity\Categorie;
use App\Entity\Evaluation;
use App\Entity\Collaborateur;
use App\Repository\PDIRepository;
use App\Repository\SkillRepository;
use App\Repository\EtoileRepository;
use App\Repository\RatingRepository;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\RatingEtoileRepository;
use App\Repository\CollaborateurRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    /**
     * @Route("/user/{id}", name="user_show" )
     */
    public function index(Collaborateur $user, PDIRepository $repoPDI, RatingRepository $repoRat, CategorieRepository $repoCat, EtoileRepository $repoEtoile, RatingEtoileRepository $repoRatetoile)
    {
        $pdis = $repoPDI->findByCollaborateur($user);
        $categorie = $repoCat->findAll();
        $etoile = $repoEtoile->findByCollaborateur($user);
        $ratings = $repoRat->findByCollaborateur($user);
        $ratingEtoiles = $repoRatetoile->findByCollaborateur($user);

        
        return $this->render('user/index.html.twig', [
            'user' => $user, 
            'categories'=>$categorie,
            'pdis' => $pdis,
            'etoiles' => $etoile,
            'ratings' => $ratings,
            'ratingEtoiles'=>$ratingEtoiles,
        ]);
    }
}
