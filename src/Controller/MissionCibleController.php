<?php

namespace App\Controller;

use App\Entity\MissionCible;
use App\Form\MissionCibleType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\MissionCibleRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("admin/etoile/mission")
 * @IsGranted("ROLE_ADMIN")
 */
class MissionCibleController extends AbstractController
{
    /**
     * @Route("/", name="mission_cible_index", methods={"GET"})
     */
    public function index(MissionCibleRepository $missionCibleRepository): Response
    {
        return $this->render('admin/etoile/mission_cible/index.html.twig', [
            'mission_cibles' => $missionCibleRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="mission_cible_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $missionCible = new MissionCible();
        $form = $this->createForm(MissionCibleType::class, $missionCible);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($missionCible);
            $entityManager->flush();

            return $this->redirectToRoute('mission_cible_index');
        }

        return $this->render('admin/etoile/mission_cible/new.html.twig', [
            'mission_cible' => $missionCible,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="mission_cible_show", methods={"GET"})
     */
    public function show(MissionCible $missionCible): Response
    {
        return $this->render('admin/etoile/mission_cible/show.html.twig', [
            'mission_cible' => $missionCible,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="mission_cible_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, MissionCible $missionCible): Response
    {
        $form = $this->createForm(MissionCibleType::class, $missionCible);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('mission_cible_index');
        }

        return $this->render('admin/etoile/mission_cible/edit.html.twig', [
            'mission_cible' => $missionCible,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Permet de suppimer une évaluation
     * @Route("/delete/{id}", name="mission_cible_delete")
     * @param MissionCible $missionCible
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function delete(Request $request, MissionCible $missionCible, EntityManagerInterface $manager): Response
    {
        $manager->remove($missionCible);
        $manager->flush();

        $this->addFlash(
            'success',
            "La mission a bien été supprimé de la base de donnée !"
        );

        return $this->redirectToRoute('mission_cible_index');
    }
}
