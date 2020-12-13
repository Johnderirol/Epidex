<?php

namespace App\Controller;

use App\Entity\CompEtoile;
use App\Form\CompEtoileType;
use App\Repository\CompEtoileRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("admin/etoile/competences")
 */
class CompEtoileController extends AbstractController
{
    /**
     * @Route("/", name="compEtoile_index", methods={"GET"})
     */
    public function index(CompEtoileRepository $compEtoileRepository): Response
    {
        return $this->render('admin/etoile/compEtoile/index.html.twig', [
            'comp_etoiles' => $compEtoileRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="compEtoile_new")
     */
    public function new(Request $request, EntityManagerInterface $manager): Response
    {
        $compEtoile = new CompEtoile();
        $form = $this->createForm(CompEtoileType::class, $compEtoile);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($compEtoile);
            $manager->flush();

            $this->addFlash(
                'success',
                "La compétence a été ajoutée !!!"
            );

            return $this->redirectToRoute('compEtoile_index');
        }

        return $this->render('admin/etoile/compEtoile/new.html.twig', [
            'compEtoile' => $compEtoile,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="compEtoile_show", methods={"GET"})
     */
    public function show(CompEtoile $compEtoile): Response
    {
        return $this->render('admin/etoile/compEtoile/show.html.twig', [
            'comp_etoile' => $compEtoile,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="compEtoile_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, CompEtoile $compEtoile): Response
    {
        $form = $this->createForm(CompEtoileType::class, $compEtoile);
        $form->handleRequest($request);
        

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('compEtoile_index');
        }

        return $this->render('admin/etoile/compEtoile/edit.html.twig', [
            'compEtoile' => $compEtoile,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Permet de suppimer une évaluation
     * @Route("/delete/{id}", name="compEtoile_delete")
     * @param CompEtoile $compEtoile
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function delete(Request $request, CompEtoile $compEtoile, EntityManagerInterface $manager): Response
    {
        $manager->remove($compEtoile);
        $manager->flush();

        $this->addFlash(
            'success',
            "La compétence a bien été supprimé de la base de donnée !"
        );

        return $this->redirectToRoute('compEtoile_index');
    }

}
