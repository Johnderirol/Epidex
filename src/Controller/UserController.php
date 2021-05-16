<?php

namespace App\Controller;

use App\Entity\Skill;
use App\Entity\Categorie;
use App\Entity\Evaluation;
use App\Entity\Collaborateur;
use App\Repository\PDIRepository;
use App\Repository\SkillRepository;
use App\Repository\EtoileRepository;
use App\Repository\LeaderRepository;
use App\Repository\RatingRepository;
use App\Repository\CategorieRepository;
use App\Repository\CompLeaderRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CollaborateurRepository;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    /**
     * @Route("/user/{id}", name="user_show" )
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_MANAGER') or is_granted('ROLE_USER') ")
     */
    public function index(Collaborateur $user, PDIRepository $repoPDI, CategorieRepository $repoCat, EtoileRepository $repoEtoile, LeaderRepository $repoLead, CompLeaderRepository $repoComp)
    {
        $pdis = $repoPDI->findByCollaborateur($user);
        $categorie = $repoCat->findAll();
        $etoile = $repoEtoile->findByCollaborateur($user);
        $leader = $repoLead->findByCollaborateur($user);
        $compLead = $repoComp->findAll();
        
        return $this->render('user/index.html.twig', [
            'user' => $user, 
            'categories'=>$categorie,
            'pdis' => $pdis,
            'etoiles' => $etoile,
            'leaders' => $leader,
            'comp' => $compLead,
        ]);
    }
}
