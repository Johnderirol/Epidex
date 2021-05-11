<?php

namespace App\Controller;

use App\Entity\Skill;
use App\Entity\Categorie;
use App\Entity\Evaluation;
use App\Entity\Collaborateur;
use App\Repository\PDIRepository;
use App\Repository\SkillRepository;
use App\Repository\EtoileRepository;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CollaborateurRepository;
use App\Repository\RatingRepository;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    /**
     * @Route("/user/{id}", name="user_show" )
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_MANAGER') or is_granted('ROLE_USER') ")
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
