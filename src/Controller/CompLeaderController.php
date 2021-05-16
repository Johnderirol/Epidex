<?php

namespace App\Controller;

use App\Entity\CompLeader;
use App\Form\CompLeaderType;
use App\Repository\CompLeaderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


/**
 * @Route("admin/leader/comp")
 * @IsGranted("ROLE_ADMIN")
 */
class CompLeaderController extends AbstractController
{
    /**
     * @Route("/", name="comp_leader_index", methods={"GET"})
     */
    public function index(CompLeaderRepository $compLeaderRepository): Response
    {
        return $this->render('admin/leader/comp_leader/index.html.twig', [
            'comp_leaders' => $compLeaderRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="comp_leader_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $compLeader = new CompLeader();
        $form = $this->createForm(CompLeaderType::class, $compLeader);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($compLeader);
            $entityManager->flush();

            return $this->redirectToRoute('comp_leader_index');
        }

        return $this->render('admin/leader/comp_leader/new.html.twig', [
            'comp_leader' => $compLeader,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="comp_leader_show", methods={"GET"})
     */
    public function show(CompLeader $compLeader): Response
    {
        return $this->render('admin/leader/comp_leader/show.html.twig', [
            'comp_leader' => $compLeader,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="comp_leader_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, CompLeader $compLeader): Response
    {
        $form = $this->createForm(CompLeaderType::class, $compLeader);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('comp_leader_index');
        }

        return $this->render('admin/leader/comp_leader/edit.html.twig', [
            'comp_leader' => $compLeader,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="comp_leader_delete", methods={"DELETE"})
     */
    public function delete(Request $request, CompLeader $compLeader): Response
    {
        if ($this->isCsrfTokenValid('delete'.$compLeader->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($compLeader);
            $entityManager->flush();
        }

        return $this->redirectToRoute('comp_leader_index');
    }
}
