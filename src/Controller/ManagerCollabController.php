<?php

namespace App\Controller;

use App\Repository\CollaborateurRepository;
use App\Repository\RayonRepository;
use App\Repository\SecteurRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ManagerCollabController extends AbstractController
{
    /**
     * @Route("/manager/collaborateur", name="manager_collab")
     */
    public function index(CollaborateurRepository $repo, SecteurRepository $repoSecteur, RayonRepository $repoRayon)
    {
        $user = $this->getUser()->getId();
        $secteur = $repoSecteur->findByResponsable($user);
        $rayon = $repoRayon->findBySecteur($secteur);

        $collaborateur = [];
        foreach ($rayon as $rayonid) {
            $collaborateur [] = $repo->findByRayon($rayonid);
        }

        return $this->render('manager/collaborateur/index.html.twig', [
            'rayons'=>$rayon,
            'collaborateur' => $collaborateur,
        ]);
    }
}
