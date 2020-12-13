<?php

namespace App\Controller;

use App\Entity\Skill;
use App\Form\SkillType;
use App\Repository\SkillRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/admin/competences/skill")
 */
class SkillController extends AbstractController
{
    /**
     * @Route("/", name="skill_index")
     */
    public function index(SkillRepository $skillRepository): Response
    {
        return $this->render('admin/competences/skill/index.html.twig', [
            'skills' => $skillRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="skill_new")
     */
    public function new(Request $request, EntityManagerInterface $manager): Response
    {
        $skill = new Skill();
        $form = $this->createForm(SkillType::class, $skill);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($skill);
            $manager->flush();

            $this->addFlash(
                'success',
                "La compétence a été ajoutée !!!"
            );

            return $this->redirectToRoute('skill_index');
        }

        return $this->render('admin/competences/skill/new.html.twig', [
            'skill' => $skill,
            'form' => $form->createView(),
        ]);
    }

    
    /**
     * @Route("/{id}", name="skill_show")
     */
    public function show(Skill $skill): Response
    {
        return $this->render('admin/competences/skill/show.html.twig', [
            'skill' => $skill,
        ]);
    }
    

    /**
     * @Route("/{id}/edit", name="skill_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Skill $skill): Response
    {
        $form = $this->createForm(SkillType::class, $skill);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('skill_index');
        }

        return $this->render('admin/competences/skill/edit.html.twig', [
            'skill' => $skill,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Permet de supprimer une compétence
     * @Route("/{id}/delete", name="skill_delete")
     * @param Skill $skill
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function delete(Skill $skill, EntityManagerInterface $manager) {
        $manager->remove($skill);
        $manager->flush();

        $this->addFlash(
            'success',
            "{$skill->getTitle()} a bien été supprimé de la base de donnée !"
        );

        return $this->redirectToRoute("skill_index");
    }

}
