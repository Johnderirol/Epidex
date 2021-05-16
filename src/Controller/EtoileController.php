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
use App\Repository\MissionCibleRepository;
use App\Repository\RatingEtoileRepository;
use App\Repository\CollaborateurRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EtoileController extends AbstractController
{
    /**
     * @Route("/admin/etoile", name="admin_etoile")
     * @IsGranted("ROLE_ADMIN")
     */
    public function indexAdmin(EtoileRepository $repo)
    {
        $etoile = $repo->findAll();

        return $this->render('etoile/indexAdmin.html.twig', [
            'etoiles' => $etoile,
        ]);
    }

    /**
     * @Route("/manager/etoile", name="manager_etoile")
     * @IsGranted("ROLE_MANAGER")
     */
    public function indexManager(EtoileRepository $repoEtoile, CollaborateurRepository $repo, SecteurRepository $repoSecteur, RayonRepository $repoRayon)
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

        return $this->render('etoile/indexManager.html.twig', [
            'etoiles' => $etoile,
        ]);
    }

    /**
     * @Route("/admin/etoile/compare/{slug}", name="compare_etoile")
     * @IsGranted("ROLE_ADMIN")
     */
    public function comparaif($slug, EtoileRepository $repo, CompEtoileRepository $compRepo , EntityManagerInterface $manager, RatingEtoileRepository $ratRepo , MissionCibleRepository $repoMission, CollaborateurRepository $repoCollab)
    {
        $missionCible = $repoMission->findBySlug($slug);
        $collaborateur = $repoCollab->findByMissionCible($missionCible);
        $note = $ratRepo->findByCollaborateur($collaborateur);
        $comps = $compRepo->findAll();

        return $this->render('admin/etoile/compare.html.twig', [
            'collaborateurs' => $collaborateur, 
            'missions' => $missionCible,
            'ratings' => $note,
            'comps' => $comps,
        ]);
    }


    /**
     * @Route("/admin/etoile/{id}/mission", name="admin_etoile_mission")
     * @Route("/manager/etoile/{id}/mission", name="manager_etoile_mission")
     * @Route("/{id}/mission", name="etoile_mission")
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_MANAGER') or is_granted('ROLE_USER')")
     */
    public function mission($id, EntityManagerInterface $manager, CollaborateurRepository $repoCollab, Request $request) 
    {    
        //Récupérer l'user
        $user = $this->getUser()->getRoles();
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
            
            if ($user[0] == 'ROLE_ADMIN') {
                return $this->redirectToRoute('admin_etoile_new', [
                    'id' => $collaborateur->getId()
                ]);
            }
            elseif ($user[0] == 'ROLE_MANAGER') {
                return $this->redirectToRoute('manager_etoile_new', [
                    'id' => $collaborateur->getId()
                ]);
            }
            else {
                return $this->redirectToRoute('etoile_new', [
                    'id' => $collaborateur->getId()
                ]);
                }
        }
        
        return $this->render('etoile/missionCible.html.twig',[
            'form' => $form->createView(),
            'editMode'=> $collaborateur->getId() !== null
        ]);
    }

    /**
     * Permet de faire une nouvelle étoile de compétence
     * @Route("/admin/etoile/new/{id}", name="admin_etoile_new", methods={"GET","POST"})
     * @Route("/manager/etoile/{id}/new", name="manager_etoile_new", methods={"GET","POST"})
     * @Route("/{id}/new", name="etoile_new", methods={"GET","POST"})
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_MANAGER') or is_granted('ROLE_USER')")
     */
    public function new($id, EntityManagerInterface $manager, CollaborateurRepository $repoCollab, CompEtoileRepository $repoComp, Request $request): Response
    {
        
        //Récupérer l'user
        $user = $this->getUser()->getRoles();
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

            if ($user[0] == 'ROLE_ADMIN') {
                return $this->redirectToRoute('admin_etoile');
            }
            elseif ($user[0] == 'ROLE_MANAGER') {
                return $this->redirectToRoute('manager_etoile');
            }
            else {
                return $this->redirectToRoute('account_index');
            }
        }

        return $this->render('etoile/new.html.twig', [
            'etoile' => $etoile,
            'form' => $form->createView(),
            'missionCible'=>$missionCible,
            
        ]);
    }

    /**
     * Permet d'éditer une étoile de compétence
     * @Route("/admin/etoile/{id}/edit", name="admin_etoile_edit", methods={"GET","POST"})
     * @Route("/manager/etoile/{id}/edit", name="manager_etoile_edit", methods={"GET","POST"})
     * @Route("/{id}/edit", name="etoile_edit", methods={"GET","POST"})
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_MANAGER') or is_granted('ROLE_USER')")
     */
    public function edit(Request $request, Etoile $etoile): Response
    {
        //Récupérer l'user
        $user = $this->getUser()->getRoles();

        $form = $this->createForm(EtoileType::class, $etoile);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            if ($user[0] == 'ROLE_ADMIN') {
                return $this->redirectToRoute('admin_etoile');
            }
            elseif ($user[0] == 'ROLE_MANAGER') {
                return $this->redirectToRoute('manager_etoile');
            }
            else {
                return $this->redirectToRoute('etoile_index');
            }
            
        }

        return $this->render('etoile/edit.html.twig', [
            'etoile' => $etoile,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/etoile/{id}", name="admin_etoile_show", methods={"GET"})
     * @Route("/manager/etoile/{id}", name="manager_etoile_show", methods={"GET"})
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_MANAGER')")
     * 
     */
    public function show(Etoile $etoile)
    {
        return $this->render('etoile/show.html.twig', [
            'etoile' => $etoile,
        ]);
    }

    /**
     * Permet de suppimer une étoile des compétences
     * @Route("/admin/etoile/delete/{id}", name="admin_etoile_delete")
     * @Route("/manager/etoile/{id}/delete", name="manager_etoile_delete")
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_MANAGER')")
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

        //Récupérer l'user
        $user = $this->getUser()->getRoles();

        if ($user[0] == 'ROLE_ADMIN') {
            return $this->redirectToRoute('admin_etoile');
        }
        elseif ($user[0] == 'ROLE_MANAGER') {
            return $this->redirectToRoute('manager_etoile');
        }
        
    }
}
