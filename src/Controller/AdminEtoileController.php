<?php

namespace App\Controller;

use App\Entity\Etoile;
use App\Form\EtoileType;
use App\Entity\RatingEtoile;
use App\Entity\Collaborateur;
use App\Repository\EtoileRepository;
use App\Repository\CompEtoileRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CollaborateurRepository;
use App\Repository\MissionCibleRepository;
use App\Repository\RatingEtoileRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminEtoileController extends AbstractController
{
    /**
     * @Route("/admin/etoile", name="admin_etoile")
     */
    public function index(EtoileRepository $repo)
    {
        $etoile = $repo->findAll();

        return $this->render('admin/etoile/index.html.twig', [
            'etoiles' => $etoile,
        ]);
    }


    /**
     * @Route("/admin/etoile/compare/{slug}", name="compare_etoile")
     */
    public function comparaif($slug, EtoileRepository $repo, CompEtoileRepository $compRepo , EntityManagerInterface $manager, RatingEtoileRepository $ratRepo , MissionCibleRepository $repoMission, CollaborateurRepository $repoCollab)
    {
        $missionCible = $repoMission->findBySlug($slug);
        $collaborateur = $repoCollab->findByMissionCible($missionCible);
        $note = $ratRepo->findByCollaborateur($collaborateur);
        $comps = $compRepo->findAll();

        dump($missionCible);
        dump($collaborateur);
        dump($comps);
        dump($note);

        return $this->render('admin/etoile/compare.html.twig', [
            'collaborateurs' => $collaborateur, 
            'missions' => $missionCible,
            'ratings' => $note,
            'comps' => $comps,
        ]);
    }


    /**
     * @Route("/admin/etoile/{id}/mission", name="admin_etoile_mission")
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
            
            
            return $this->redirectToRoute('admin_etoile_new', [
                'id' => $collaborateur->getId()
            ]);
        }
        
        return $this->render('admin/etoile/missionCible.html.twig',[
            'form' => $form->createView(),
            'editMode'=> $collaborateur->getId() !== null
        ]);
    }

    /**
     * @Route("/admin/etoile/new/{id}", name="admin_etoile_new", methods={"GET","POST"})
     */
    public function new($id, EntityManagerInterface $manager, CollaborateurRepository $repoCollab, CompEtoileRepository $repoComp, Request $request): Response
    {
        $comp = $repoComp->findAll();

        //Récupérer Collaborateur grâce au Id
        $collaborateur = $repoCollab->findOneById($id);
        $missionCible = $collaborateur->getMissionCible();
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
                $rating->setCollaborateur($collaborateur);
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

            return $this->redirectToRoute('admin_etoile');
        }

        return $this->render('admin/etoile/new.html.twig', [
            'etoile' => $etoile,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/etoile/{id}/edit", name="admin_etoile_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Etoile $etoile): Response
    {
        $form = $this->createForm(EtoileType::class, $etoile);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_etoile');
        }

        return $this->render('admin/etoile/edit.html.twig', [
            'etoile' => $etoile,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/etoile/{id}", name="admin_etoile_show", methods={"GET"}, requirements={"id":"\d+"}))
     */
    public function show(Etoile $etoile)
    {
        return $this->render('admin/etoile/show.html.twig', [
            'etoile' => $etoile,
        ]);
    }

    /**
     * Permet de suppimer une étoile des compétences
     * @Route("/admin/etoile/delete/{id}", name="admin_etoile_delete")
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

        return $this->redirectToRoute('admin_etoile');
    }
}
