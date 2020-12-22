<?php

namespace App\Controller;

use App\Entity\Skill;
use App\Entity\Rating;
use App\Entity\Evaluation;
use App\Form\EvaluationType;
use App\Entity\Collaborateur;
use App\Repository\SkillRepository;
use App\Repository\RatingRepository;
use App\Repository\EvaluationRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CollaborateurRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/evaluation")
 */
class EvaluationController extends AbstractController
{

    /**
     * @Route("/new/{id}", name="evaluation_new")
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_MANAGER') or is_granted('ROLE_USER') ")
     */
    public function new($id, Request $request, EntityManagerInterface $manager, CollaborateurRepository $repoCollab, SkillRepository $repoSkill)
    {
        $comp = $repoSkill->findAll();

        //Récupérer l'user
        $user = $this->getUser()->getRoles();

        //Récupérer Collaborateur grâce au Id
        $collaborateur = $repoCollab->findOneById($id);
        $rayon = $collaborateur->getRayon();
        $missionCollab = $repoCollab->findOneById($id)->getMission();
        $missionId = $missionCollab->getId();

        //DQL pour les skills correspondantes à la mission
        $query = $manager->createQuery(
        "SELECT s.id
        FROM App\Entity\Skill s
        LEFT JOIN s.missions m 
        WHERE m.id = " .$missionId
        );
        $skills = $query->getResult();


        //Nouvelle évaluation
        $evaluation = new Evaluation(); 

        //On demande un array de compétences sélectionnées dans $skills
        foreach ($skills as $skillId) {
        $rating = new Rating ();
        $skillT = $repoSkill->findOneById($skillId);
        $rating->setCompetences($skillT);
        $rating->setCollaborateur($collaborateur);
        $rating->setRayon($rayon);
        $evaluation->addRating($rating);
        }
    
        $form = $this->createForm(EvaluationType::class, $evaluation);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){ 
            foreach ($evaluation->getRatings() as $rating){
                $rating->setEvaluation($evaluation);
                $rating->setRayon($rayon);
                $manager->persist($rating);
            }
            $evaluation->setAuteur($this->getUser());
            $evaluation->setCollaborateur($collaborateur);
            $evaluation->setCreatedAt(new \DateTime());
            $manager->persist($evaluation);
            $manager->flush();

            $this->addFlash(
                'success',
                "L'évaluation est prise en compte !!!"
            );
            
            if ($user[0] == 'ROLE_ADMIN') {
                return $this->redirectToRoute('admin_eval');
            }
            elseif ($user[0] == 'ROLE_MANAGER') {
                return $this->redirectToRoute('manager_eval');
            }
            else {
                return $this->redirectToRoute('account_index');
            }
            
            
        }

        return $this->render('evaluation/new.html.twig', [
            'evaluation' => $evaluation,
            'collaborateur' => $collaborateur,
            'comps'=>$comp,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/{id}/{slug}", name="evaluation_show")
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_MANAGER')")
     * 
     */
    public function show (Evaluation $evaluation)
    {   
        
        return $this->render('evaluation/show.html.twig', [
            'evaluation' => $evaluation,
        ]);
    }

    /**
     * @Route("/{id}/edit/{slug}", name="evaluation_edit")
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_MANAGER') or is_granted('ROLE_USER') ")
     */
    public function edit($slug, Request $request, Evaluation $evaluation,CollaborateurRepository $repoCollab): Response
    {
        //Récupérer l'user
        $user = $this->getUser()->getRoles();

        $collaborateur = $repoCollab->findOneBySlug($slug);
        $form = $this->createForm(EvaluationType::class, $evaluation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            if ($user[0] = 'ROLE_ADMIN') {
                return $this->redirectToRoute('admin_eval');
            }
            elseif ($user[0] = 'ROLE_MANAGER') {
                return $this->redirectToRoute('manager_eval');
            }
            else {
                return $this->redirectToRoute('account_index');
            }
        }

        return $this->render('evaluation/edit.html.twig', [
            'evaluation' => $evaluation,
            'collaborateur' => $collaborateur,
            'form' => $form->createView(),
        ]);
    }


    /**
     * Permet de suppimer une évaluation
     * @Route("/admin/delete_evaluation/{id}", name="admin_evaluation_delete")
     * @Route("/manager/delete_evaluation/{id}", name="manager_evaluation_delete")
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_MANAGER')")
     * @param Evaluation $evaluation
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function delete(Evaluation $evaluation, EntityManagerInterface $manager) {
        $manager->remove($evaluation);
        $manager->flush();

        $this->addFlash(
            'success',
            "L'évaluation a bien été supprimé de la base de donnée !"
        );

        //Récupérer l'user
        $user = $this->getUser()->getRoles();

        if ($user[0] == 'ROLE_ADMIN') {
            return $this->redirectToRoute('admin_eval');
        }
        elseif ($user[0] == 'ROLE_MANAGER') {
            return $this->redirectToRoute('manager_eval');
        }
    }

}
