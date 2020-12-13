<?php

namespace App\Controller;

use App\Entity\PDI;
use App\Form\PDIType;
use App\Repository\PDIRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CollaborateurRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminPDIController extends AbstractController
{
    /**
     * @Route("/admin/pdi/", name="admin_pdi")
     */
    public function index(PDIRepository $repo)
    {
        $pdi = $repo->findAll();

        return $this->render('admin/pdi/index.html.twig', [
            'pdis' => $pdi,
        ]);
    } 

    /**
     * @Route("/admin/new/{id}", name="admin_pdi_new", methods={"GET","POST"})
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

            return $this->redirectToRoute('admin_pdi');
        }

        return $this->render('admin/pdi/new.html.twig', [
            'pdi' => $PDI,
            'collaborateur' => $collaborateur,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/{id}", name="admin_pdi_show", methods={"GET"}, requirements={"id":"\d+"}))
     */
    public function show(PDI $pdi): Response
    {
        return $this->render('admin/pdi/show.html.twig', [
            'pdi' => $pdi,
        ]);
    }

    /**
     * @Route("/admin/{id}/edit", name="admin_pdi_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, PDI $pDI): Response
    {
        $form = $this->createForm(PDIType::class, $pDI);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_pdi');
        }

        return $this->render('admin/pdi/edit.html.twig', [
            'pdi' => $pDI,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Permet de suppimer une évaluation
     * @Route("/admin/{id}/delete", name="admin_pdi_delete")
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

        return $this->redirectToRoute('admin_pdi');
    }
}
