<?php

namespace App\Controller;

use App\Entity\Mission;
use App\Form\MissionType;
use App\Repository\MissionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/admin/mission")
 */
class MissionController extends AbstractController
{

    /**
     * @Route("/", name="mission_index", methods={"GET"})
     */
    
    public function index(MissionRepository $missionRepository): Response
    {
        return $this->render('admin/competences/mission/index.html.twig', [
            'missions' => $missionRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="mission_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $mission = new Mission();
        $form = $this->createForm(MissionType::class, $mission);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($mission);
            $entityManager->flush();

            return $this->redirectToRoute('mission_index');
        }

        return $this->render('admin/competences/mission/new.html.twig', [
            'mission' => $mission,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="mission_show", methods={"GET"})
     */
    public function showMission($id, Mission $mission, EntityManagerInterface $manager) 
    {
        $query = $manager->createQuery(
            "SELECT AVG(r.note) as note, s.title
            FROM App\Entity\Collaborateur c
            JOIN c.evaluations e
            JOIN c.mission m
            JOIN e.ratings r
            JOIN r.competences s
            WHERE m.id =$id
            GROUP BY s.title");
        $evalMission= $query->getResult();

        return $this->render('admin/competences/mission/show.html.twig',[
            'mission'=>$mission,
            'evalMission'=>$evalMission
        ]);
    }

    /**
     * @Route("/{id}/edit", name="mission_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Mission $mission): Response
    {
        $form = $this->createForm(MissionType::class, $mission);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('mission_index');
        }

        return $this->render('admin/competences/mission/edit.html.twig', [
            'mission' => $mission,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Permet de suppimer une évaluation
     * @Route("/delete/{id}", name="mission_delete")
     * @param Mission $mission
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function delete(Request $request, Mission $mission, EntityManagerInterface $manager): Response
    {
        $manager->remove($mission);
        $manager->flush();

        $this->addFlash( 
            'success',
            "La mission a bien été supprimé de la base de donnée !"
        );

        return $this->redirectToRoute('mission_index');
    }

}
