<?php

namespace App\Controller;

use App\Entity\Etoile;
use App\Form\EtoileType;
use App\Entity\RatingEtoile;
use App\Entity\Collaborateur;
use App\Form\CollaborateurType;
use App\Repository\RayonRepository;
use App\Repository\EtoileRepository;
use App\Repository\SecteurRepository;
use App\Repository\CompEtoileRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CollaborateurRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ManagerEtoileController extends AbstractController
{

    /**
     * @Route("/manager/etoile/{id}/mission", name="manager_etoile_mission")
     */
    public function mission($id, EntityManagerInterface $manager, CollaborateurRepository $repoCollab, Request $request) 
    {    
        //Récupérer Collaborateur grâce au Id
        $collaborateur = $repoCollab->findOneById($id);

        if(!$collaborateur){
            $collaborateur = new Collaborateur();
        }
        
        $form = $this->createForm(CollaborateurType::class, $collaborateur);
        
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){        
            $manager->persist($collaborateur);
            $manager->flush();
            
            $this->addFlash(
                'success',
                "Passons à la deuxième étape!!!"
            );
            
            
            return $this->redirectToRoute('manager_etoile_new', [
                'id' => $collaborateur->getId()
            ]);
        }
        
        return $this->render('manager/etoile/missionCible.html.twig',[
            'form' => $form->createView(),
            'editMode'=> $collaborateur->getId() !== null
        ]);
    }

    /**
     * @Route("/manager/etoile/{id}/new", name="manager_etoile_new", methods={"GET","POST"})
     */
    public function new($id, EntityManagerInterface $manager, CollaborateurRepository $repoCollab, CompEtoileRepository $repoComp, Request $request): Response
    {
        $comp = $repoComp->findAll();

        //Récupérer Collaborateur grâce au Id
        $collaborateur = $repoCollab->findOneById($id);
        $missionCible = $repoCollab->findOneById($id)->getMissionCible();
        $missionId = $missionCible->getId();

        //DQL pour les Comp correspondantes à la mission
        $query = $manager->createQuery(
        "SELECT c.id
        FROM App\Entity\CompEtoile c
        LEFT JOIN c.missionCibles m 
        WHERE m.id = " .$missionId
        );
        $comps = $query->getResult();

        //Nouvelle évaluation
        $etoile = new Etoile();

        //On demande un array de compétences sélectionnées dans $compEtoile
        foreach ($comps as $compId) {
            $rating = new RatingEtoile ();
            $compT = $repoComp->findOneById($compId);
            $rating ->setCompetences($compT);
            $etoile->addRatingEtoile($rating);
            }

        $form = $this->createForm(EtoileType::class, $etoile);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($etoile->getRatingEtoiles()as $rating) {
                $rating->setEtoile($etoile);
                $manager->persist($rating);
            }
            $etoile->setAuteur($this->getUser());
            $etoile->setCollaborateur($collaborateur);
            $etoile->setCreatedAt(new \DateTime());
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($etoile);
            $manager->flush();

            $this->addFlash(
                'success',
                "L'évaluation est prise en compte !!!"
            );

            return $this->redirectToRoute('manager_etoile');
        }

        return $this->render('manager/etoile/new.html.twig', [
            'etoile' => $etoile,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/manager/etoile/{id}/edit", name="manager_etoile_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Etoile $etoile): Response
    {
        $form = $this->createForm(EtoileType::class, $etoile);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('manager_etoile');
        }

        return $this->render('manager/etoile/edit.html.twig', [
            'etoile' => $etoile,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/manager/etoile", name="manager_etoile")
     */
    public function index(EtoileRepository $repoEtoile, CollaborateurRepository $repo, SecteurRepository $repoSecteur, RayonRepository $repoRayon)
    {

        $user = $this->getUser()->getId();
        $secteur = $repoSecteur->findByResponsable($user);
        $rayon = $repoRayon->findBySecteur($secteur);

        $collaborateur = [];
        foreach ($rayon as $rayonid) {
            $collaborateur [] = $repo->findByRayon($rayonid);
        }
        
        $etoile = [];
        foreach ($collaborateur as $collaborateurs){
            $etoile [] = $repoEtoile->findByCollaborateur($collaborateurs);
        }

        return $this->render('manager/etoile/index.html.twig', [
            'etoiles' => $etoile,
        ]);
    }

    /**
     * @Route("/manager/etoile/{id}", name="manager_etoile_show", methods={"GET"})
     */
    public function show(Etoile $etoile)
    {
        return $this->render('manager/etoile/show.html.twig', [
            'etoile' => $etoile,
        ]);
    }

    /**
     * Permet de suppimer une étoile des compétences
     * @Route("manager/etoile/{id}/delete", name="manager_etoile_delete")
     * @param Etoile $etoile
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function delete(Etoile $etoile, EntityManagerInterface $manager) {
        $manager->remove($etoile);
        $manager->flush();

        $this->addFlash(
            'success',
            "L'étoile des compétence a bien été supprimé de la base de donnée !"
        );

        return $this->redirectToRoute('manager_etoile');
    }
}
