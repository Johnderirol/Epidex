<?php

namespace App\Controller;

use App\Entity\Skill;
use App\Entity\Categorie;
use App\Entity\Evaluation;
use App\Entity\Collaborateur;
use App\Repository\CategorieRepository;
use App\Repository\PDIRepository;
use App\Repository\SkillRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CollaborateurRepository;
use App\Repository\EtoileRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    /**
     * @Route("/user/{id}", name="user_show" )
     */
    public function index(Collaborateur $user, PDIRepository $repoPDI, CategorieRepository $repoCat, EtoileRepository $repoEtoile)
    {
        $pdis = $repoPDI->findByCollaborateur($user);
        $categorie = $repoCat->findAll();
        $etoile = $repoEtoile->findByCollaborateur($user);

        
        return $this->render('user/index.html.twig', [
            'user' => $user, 
            'categories'=>$categorie,
            'pdis' => $pdis,
            'etoiles' => $etoile,
        ]);
    }
}
