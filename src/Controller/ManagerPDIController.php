<?php

namespace App\Controller;

use App\Entity\PDI;
use App\Form\PDIType;
use App\Repository\PDIRepository;
use App\Repository\RayonRepository;
use App\Repository\SecteurRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CollaborateurRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ManagerPDIController extends AbstractController
{
    /**
     * @Route("/manager/pdi/", name="manager_pdi")
     */
    public function index(PDIRepository $repoPDI, CollaborateurRepository $repo, SecteurRepository $repoSecteur, RayonRepository $repoRayon)
    {
        $user = $this->getUser()->getId();
        $secteur = $repoSecteur->findByResponsable($user);
        $rayon = $repoRayon->findBySecteur($secteur);

        $collaborateur = [];
        foreach ($rayon as $rayonid) {
            $collaborateur [] = $repo->findByRayon($rayonid);
        }
        
        $pdi = [];
        foreach ($collaborateur as $collaborateurs){
            $pdi [] = $repoPDI->findByCollaborateur($collaborateurs);
        }

        return $this->render('manager/pdi/index.html.twig', [
            'pdis' => $pdi,
        ]);
    }

    /**
     * @Route("/manager/new/{id}", name="manager_pdi_new", methods={"GET","POST"})
     */
    public function new($id, Request $request, EntityManagerInterface $manager, CollaborateurRepository $repoCollab): Response
    {
        $collaborateur = $repoCollab->findOneById($id);
        $PDI = new PDI();
        $form = $this->createForm(PDIType::class, $PDI);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $PDI->setAuteur($this->getUser());
            $PDI->setCollaborateur($collaborateur);
            $PDI->setCreatedAt(new \DateTime());
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($PDI);
            $manager->flush();

            return $this->redirectToRoute('manager_pdi');
        }

        return $this->render('manager/pdi/new.html.twig', [
            'pdi' => $PDI,
            'collaborateur' => $collaborateur,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/manager/{id}", name="manager_pdi_show", methods={"GET"})
     */
    public function show(PDI $PDI): Response
    {
        return $this->render('manager/pdi/show.html.twig', [
            'pdi' => $PDI,
        ]);
    }

    /**
     * @Route("/manager/{id}/edit", name="manager_pdi_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, PDI $pDI): Response
    {
        $form = $this->createForm(PDIType::class, $pDI);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('manager_pdi');
        }

        return $this->render('manager/pdi/edit.html.twig', [
            'pdi' => $pDI,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Permet de suppimer une évaluation
     * @Route("/manager/{id}/delete", name="manager_pdi_delete")
     * @param PDI $pdi
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function delete(PDI $pdi, EntityManagerInterface $manager) {
        $manager->remove($pdi);
        $manager->flush();

        $this->addFlash(
            'success',
            "Le PDI a bien été supprimé de la base de donnée !"
        );

        return $this->redirectToRoute('manager_pdi');
    }
}


