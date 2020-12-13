<?php

namespace App\Controller;

use App\Entity\Etoile;
use App\Form\EtoileType;
use App\Entity\RatingEtoile;
use App\Entity\Collaborateur;
use App\Form\CollaborateurType;
use Doctrine\ORM\EntityManager;
use App\Repository\EtoileRepository;
use App\Repository\CompEtoileRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CollaborateurRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


/**
 * @Route("/etoile")
 */
class EtoileController extends AbstractController
{
    /**
     * @Route("/", name="etoile_index", methods={"GET"})
     */
    public function index(EtoileRepository $etoileRepository): Response
    {
        return $this->render('etoile/index.html.twig', [
            'etoiles' => $etoileRepository->findAll(),
        ]);
    }

    /**
     * @Route("/{id}/mission", name="etoile_mission")
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
            
            
            return $this->redirectToRoute('etoile_new', [
                'id' => $collaborateur->getId()
            ]);
        }
        
        return $this->render('admin/etoile/missionCible.html.twig',[
            'form' => $form->createView(),
            'editMode'=> $collaborateur->getId() !== null
        ]);
    }

    /**
     * @Route("/{id}/new", name="etoile_new", methods={"GET","POST"})
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

            return $this->redirectToRoute('account_index');
        }

        return $this->render('etoile/new.html.twig', [
            'etoile' => $etoile,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="etoile_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Etoile $etoile): Response
    {
        $form = $this->createForm(EtoileType::class, $etoile);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('etoile_index');
        }

        return $this->render('etoile/edit.html.twig', [
            'etoile' => $etoile,
            'form' => $form->createView(),
        ]);
    }
}
